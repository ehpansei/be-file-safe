<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// auth
Route::post('/authentication', [AuthenticationController::class, 'login']);

// authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // files
    Route::post('/files', [FileController::class, 'create']);
    Route::delete('/files/{file}', [FileController::class, 'delete']);
    Route::get('/files', [FileController::class, 'get']);
    Route::get('/files/{file}', [FileController::class, 'show']);
    Route::get('/files/{file}/download', [FileController::class, 'download']);

    // auth
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/renew', [AuthenticationController::class, 'renew']);
});

Route::get('/test', function (Request $request) {
    return response()->json(["message" => "hello"]);
});
