<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        $user = $request->user()->toArray();
        $roles = $request->user()->roles()->pluck('name');
        $user['roles'] = $roles;
        return $user;
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/my_roles', [RoleController::class, 'my_roles']);
    Route::get('/exams', [ExamController::class, 'index']);
    Route::get('/exams/{id}', [ExamController::class, 'show']);

    Route::middleware('role:user')->group(function () {

    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::controller(UserController::class)->group(function() {
            Route::get('/users', [UserController::class, 'index']);
        });

        Route::controller(ExamController::class)->group(function() {
            Route::post('/exams/{id}', [ExamController::class, 'store']);
            Route::delete('/exams/{id}', [ExamController::class, 'delete']);
            Route::patch('/exams/{id}', [ExamController::class, 'update']);
        });
    });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
