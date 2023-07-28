<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $users = User::paginate(5);

        return $this->sendResponse($users, 'Get users completed!');
    }
}
