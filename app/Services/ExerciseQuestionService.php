<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Exercise;
use App\Models\ExerciseQuestion;
use App\Models\Question;
use Exception;

class ExerciseQuestionService
{
    public function initialExistExercise(Exercise $exercise)
    {
        $exercise_questions = array_fill(0, count($exercise['exercise_questions']), 0);
        foreach ($exercise['exercise_questions'] as $value) {
            $exercise_questions[$value['position']] = $this->getExerciseQuestion($value, $value['question']);
        }

        return $exercise_questions;
    }

    public function generateQuestions(Exam $exam, Exercise $exercise)
    {
        $questions = $exam->questions;
        $positions = $questions->pluck('id')->toArray();
        shuffle($positions);
        $exercise_questions = array_fill(0, count($positions), 0);

        foreach ($questions as $value) {
            $position = array_search($value->id, $positions);
            $exercise_question = ExerciseQuestion::create([
                'exercise_id' => $exercise->id,
                'question_id' => $value->id,
                'position' => $position,
            ]);
            $exercise_questions[$position] = $this->getExerciseQuestion($exercise_question, $value);
        }

        return $exercise_questions;
    }

    public function submit($exercise, $questions)
    {
        try {
            $data = $this->initializeExerciseQuestions($exercise->toArray(), $questions);
            foreach ($data as $d) {
                $ex_question = $exercise->exercise_questions()->where(['id' => $d['id']])->firstOrFail();
                $ex_question->update(['value' => $d['value']]);
                $ex_question->exercise_answers()->createMany($d['answers']);
            }
            return true;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function getExerciseQuestion(ExerciseQuestion $exercise_question, Question $question)
    {
        $data = $exercise_question->setVisible(['id', 'exercise_id', 'position', 'value'])->toArray();
        $data = array_merge($data, $question->setVisible(['content', 'type'])->toArray());
        $data['answers'] = $this->loadAnswers($question, $exercise_question['exercise_answers']->toArray());

        return $data;
    }

    private function loadAnswers(Question $question, $exercise_answers = null)
    {
        $ex_answer_ids = $exercise_answers && count($exercise_answers) > 0 ? $this->mapWithId($exercise_answers, 'answer_id') : [];
        $questions = $question->answers()->select('id', 'content')->get()->toArray();
        return array_map(function ($i) use ($ex_answer_ids) {
            $i['selected'] = in_array($i['id'], $ex_answer_ids);
            return $i;
        }, $questions);
    }

    private function initializeExerciseQuestions($exercise, $ex_questions)
    {
        return array_map(function ($i) use ($exercise) {
            $questions = array_filter($exercise['exercise_questions'], function ($q) use ($i) {
                return $q['id'] === $i['id'];
            });

            $question = count($questions) > 0 ? $questions[0]['question'] : array('answers' => []);

            $selected_answer = array_filter($i['answers'], function ($ans) use ($question) {
                return $ans['selected'] && in_array($ans['id'], $this->mapWithId($question['answers']));
            });
            $selected_answer_ids = array_map(function ($ans) {
                return ['answer_id' => $ans['id']];
            }, $selected_answer);

            $selected_ids = $this->mapWithId($selected_answer);
            $answer_ids = $this->mapWithId(array_filter($question['answers'], function ($ans) {
                return $ans['correct'];
            }));
            $is_correct = sort($selected_ids, SORT_NUMERIC) && sort($answer_ids, SORT_NUMERIC) && $selected_ids == $answer_ids;

            return ['id' => $i['id'], 'answers' => $selected_answer_ids, 'value' => $is_correct];
        }, $ex_questions);
    }

    private function mapWithId($arr, $field = 'id')
    {
        return array_map(function ($a) use($field) {
            return $a[$field];
        }, $arr);
    }
}
