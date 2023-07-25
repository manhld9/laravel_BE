<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends BaseController
{
    public function my_roles(Request $request): JsonResponse {
        $roles = Auth::user()->roles()->pluck('name');
        return $this->sendResponse([ 'roles' => $roles ], 'User Roles');
    }
}
