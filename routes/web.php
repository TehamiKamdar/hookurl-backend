<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
Route::get('/auth/user', function (Request $request) {

    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
    ]);

});

Route::middleware('auth')->group(function () {
    Route::post('/links', [LinkController::class, 'store']);
});

Route::post("/register", [AuthController::class , 'register'])->name('register');
Route::post("/login", [AuthController::class , 'login'])->name('login');


// Links fetch and create from homepage
Route::get('/', [HomeController::class , 'index']);


// Custom alias checking
Route::get('/alias-check/{alias?}', [LinkController::class , 'aliasCheck']);

// For Dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('', [LinkController::class , 'index']);
    // Route::post('', [LinkController::class , 'store']);
});