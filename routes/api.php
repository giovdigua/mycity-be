<?php

use App\Http\Controllers\API\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::get('/email/verify', function () {
    return null;
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [RegisterController::class, 'send'])->middleware([ 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', [RegisterController::class, 'user']);
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::get('users', [UserController::class, 'index'])->can('viewAny',User::class);
    Route::delete('users/{userId}', [UserController::class, 'destroy']);
    Route::put('users/{user}', [UserController::class, 'update']);
});
