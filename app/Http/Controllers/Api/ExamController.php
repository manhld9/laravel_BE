<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
