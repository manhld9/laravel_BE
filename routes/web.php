<?php

use App\Http\Controllers\CustomAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function() {
    Route::controller(CustomAuthController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/signout', 'signOut')->name('signout');
    });
});

Route::middleware(['guest'])->group(function() {
    Route::get('/login', [CustomAuthController::class, 'index'])->name('login');
    Route::post('/custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
    Route::get('/registration', [CustomAuthController::class, 'registration'])->name('register-user');
    Route::post('/custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
});
