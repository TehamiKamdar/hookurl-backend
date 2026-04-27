<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LinkController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Links fetch and create from homepage
Route::get('/', [HomeController::class , 'index']);
Route::post('/', [LinkController::class , 'store']);


// Auth APIs
Route::post("/register", [AuthController::class , 'register']);
Route::post("/login", [AuthController::class , 'login']);


// For Dashboard
Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('links')->group(function () {
        Route::get('', [LinkController::class , 'index']);
    });
});
