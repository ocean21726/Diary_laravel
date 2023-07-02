<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiaryController;
use App\Models\Diary;

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

Route::prefix('user')->group(function () {
    Route::post('sign-up', [UserController::class, 'postUserSignUp']);
    Route::get('id-check', [UserController::class, 'getUserIdCheck']);
    Route::post('login', [UserController::class, 'postUserLogin']);
    Route::get('find', [UserController::class, 'getUserFind'])->middleware('jwt.auth');
});

Route::prefix('diary')->group(function () {
    Route::post('create', [DiaryController::class, 'postDiaryCreate'])->middleware('jwt.auth');
    Route::get('list', [DiaryController::class, 'getDiaryList'])->middleware('jwt.auth');
});

Route::prefix('page')->middleware('jwt.auth')->group(function () {
    Route::post('create', [DiaryController::class, 'postPageCreate']);
    Route::put('update', [DiaryController::class, 'putPageUpdate']);
    Route::get('list', [DiaryController::class, 'getPageList']);
    Route::get('detail', [DiaryController::class, 'getPageDetail']);
});
