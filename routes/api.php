<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LinkController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', [HomeController::class , 'index']);

Route::post("/register", [AuthController::class , 'register']);
Route::post("/login", [AuthController::class , 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('links')->group(function () {
        Route::get('', [LinkController::class , 'index']);
    });
});
Route::post('', [LinkController::class , 'store']);
