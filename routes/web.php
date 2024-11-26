<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::prefix('api')->middleware('api')->group(function () {
//     Route::get('/folders', [FolderController::class, 'index']);
//     Route::post('/folder/store', [FolderController::class, 'store']);

//     Route::get('/excels', [ExcelController::class, 'index']);
//     Route::post('/excel/store', [ExcelController::class, 'store']);

//     Route::get('/images', [ImageController::class, 'index']);
//     Route::post('/image/store', [ImageController::class, 'store']);
// });