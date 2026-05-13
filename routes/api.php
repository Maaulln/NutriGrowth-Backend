<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\FoodController;

// ── Auth routes (tanpa middleware) ────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ── Auth routes (butuh token) ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // ── Child routes (scoped to parent) ──────────────────────────────────────
    Route::apiResource('children', ChildController::class);
});

// ── Admin dashboard (tidak berubah) ───────────────────────────────────────────
Route::apiResource('users', UserController::class);

// ── Food routes ──────────────────────────────────────────────────────────────
Route::get('foods', [FoodController::class, 'index']);
Route::get('foods/{id}', [FoodController::class, 'show']);

