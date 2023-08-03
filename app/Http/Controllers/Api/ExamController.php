<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\AliveTrueAnswerRule;

class ExamController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $exams = Exam::paginate(15);

        return $this->sendResponse($exams, 'Get exams success.');
    }

    public function show(Exam $exam): JsonResponse
    {
        return $this->sendResponse($exam, 'Get exam success.');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
                'limit_time' => ['required'],
                'level' => ['required'],
                'questions' => ['required', 'array', 'min:1'],
                'questions.*.content' => ['required'],
                'questions.*.answers' => ['required', 'array', 'min:2'],
                'questions.*.answers.*.content' => ['required'],
                'questions.*.answers.*.correct' => ['boolean']
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 401);
            }

            // return $this->sendResponse($request->questions[0]['content'], 'Create exam completed.');
            $exam = Exam::create([
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'level' => $request->level,
                'description' => $request->description,
                'limit_time' => $request->limit_time
            ]);

            foreach($request->questions as $data) {
                $question = Question::create([
                    'exam_id' => $exam->id,
                    'content' => $data['content'],
                    'type' => $data['type']
                ]);

                $question->answers()->createMany($data['answers']);
            }

            return $this->sendResponse('test', 'Create exam completed.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
