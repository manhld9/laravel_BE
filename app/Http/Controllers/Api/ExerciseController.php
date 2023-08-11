<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Exercise;
use App\Services\ExerciseQuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExerciseController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new ExerciseQuestionService();
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $exam = Exam::with(['questions', 'questions.answers', 'user'])->findOrFail($request->exam_id);
            $exercises = $exam->exercises()->with(['exercise_questions.question.answers'])
                ->where(['user_id' => $request->user()->id])->get();

            if (!$exercises->isEmpty()) {
                if ($exercises->first()->submitted_at) {
                    return $this->sendError('You completed exam!', [], 200);
                }

                $exercise = $exercises->first();
                $questions = $this->service->initialExistExercise($exercise);
            } else {
                $exercise = Exercise::create([
                    'exam_id' => $exam->id,
                    'user_id' => $request->user()->id
                ]);
                $questions = $this->service->generateQuestions($exam, $exercise);
            }

            $data = $exercise->toArray();
            $data['questions'] = $questions;
            $data['exam'] = $exam->setHidden(['questions', 'user.id', 'user.email']);

            return $this->sendResponse($data, 'Enrolled Exam!');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function submit(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $exercise = Exercise::with(['exercise_questions.question.answers'])
                ->where([
                    'user_id' => $request->user()->id,
                    'id' => $request->id,
                    'exam_id' => $request->exam_id,
                    'submitted_at' => null
                ])
                ->firstOrFail();

            $exercise->update(['submitted_at' => now()]);
            $data = $this->service->submit($exercise, $request->questions);
            DB::commit();
            return $this->sendResponse($data, 'Submit Completed!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function show(Request $request): JsonResponse
    {
        try {
            $exercise = Exercise::with(['exercise_questions.exercise_answers', 'exercise_questions.question.answers'])
                ->where([
                    'user_id' => $request->user()->id,
                    'id' => $request->id,
                    'exam_id' => $request->exam_id
                ])->firstOrFail();

            $questions = $this->service->initialExistExercise($exercise);

            $data = $exercise->toArray();
            $data['questions'] = $questions;
            $data['exam'] = $exercise->exam()->with(['user'])->first();

            return $this->sendResponse($data, 'Enrolled Exam!');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
