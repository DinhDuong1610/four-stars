<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/folders', [FolderController::class, 'index']);
Route::post('/folder/store', [FolderController::class, 'store']);
Route::get('/show', [FolderController::class, 'show']);
Route::post('/folder/parent', [FolderController::class, 'getFolderParent']);
Route::post('/folder/current', [FolderController::class, 'getFolderCurrent']);

Route::get('/excels', [ExcelController::class, 'index']);
Route::post('/excel/store', [ExcelController::class, 'store']);

Route::get('/images', [ImageController::class, 'index']);
Route::post('/image/store', [ImageController::class, 'store']);

Route::get('/students', [StudentController::class, 'index']);
Route::post('/student/store', [StudentController::class, 'store']);
Route::post('/student/update', [StudentController::class, 'update']);
Route::post('/student/updateMultiple', [StudentController::class, 'updateMultiple']);