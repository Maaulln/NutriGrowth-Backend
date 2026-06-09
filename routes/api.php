<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\AnalysisController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\MealPlanController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AdminChildController;

// ── Autentikasi publik (tidak perlu token) ────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ── Data makanan publik (tidak perlu token) ───────────────────────────────────
Route::get('foods',       [FoodController::class, 'index']);
Route::get('foods/{id}',  [FoodController::class, 'show']);

// ── Waitlist publik (landing page) ───────────────────────────────────────────
Route::post('waitlist', [WaitlistController::class, 'store']);

// ── Route yang memerlukan token (user biasa) ──────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // Analisis gizi anak
    Route::post('analyze',  [AnalysisController::class, 'analyze']);
    Route::get('analyses',  [AnalysisController::class, 'index']);

    // Notifikasi
    Route::get('notifications',                    [NotificationController::class, 'index']);
    Route::patch('notifications/read-all',         [NotificationController::class, 'readAll']);
    Route::patch('notifications/{id}/read',        [NotificationController::class, 'markRead']);
    Route::delete('notifications/{id}',            [NotificationController::class, 'destroy']);

    // CRUD data anak (hanya milik sendiri)
    Route::apiResource('children', ChildController::class);
    Route::get('children/{childId}/assessments',    [AnalysisController::class, 'childAssessments']);
    Route::get('children/{childId}/growth-records', [ChildController::class, 'growthRecords']);

    // Rencana makan (meal plans)
    Route::get('meal-plans',                                    [MealPlanController::class, 'index']);
    Route::post('meal-plans',                                   [MealPlanController::class, 'store']);
    Route::get('meal-plans/{id}',                               [MealPlanController::class, 'show']);
    Route::put('meal-plans/{id}',                               [MealPlanController::class, 'update']);
    Route::delete('meal-plans/{id}',                            [MealPlanController::class, 'destroy']);
    Route::post('meal-plans/{id}/items',                        [MealPlanController::class, 'addItem']);
    Route::put('meal-plans/{id}/items/{itemId}',                [MealPlanController::class, 'updateItem']);
    Route::delete('meal-plans/{id}/items/{itemId}',             [MealPlanController::class, 'removeItem']);
});

// ── Route admin (token + role admin) ─────────────────────────────────────────
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Manajemen user
    Route::apiResource('users', UserController::class);

    // Data anak semua user
    Route::get('admin/children',              [AdminChildController::class, 'index']);
    Route::get('admin/users/{id}/children',   [AdminChildController::class, 'byUser']);

    // CRUD makanan (admin)
    Route::post('admin/foods',        [FoodController::class, 'store']);
    Route::put('admin/foods/{id}',    [FoodController::class, 'update']);
    Route::delete('admin/foods/{id}', [FoodController::class, 'destroy']);

    // Waitlist (manajemen — POST sudah public di atas)
    Route::get('waitlist',        [WaitlistController::class, 'index']);
    Route::get('waitlist/{id}',   [WaitlistController::class, 'show']);
    Route::put('waitlist/{id}',   [WaitlistController::class, 'update']);
    Route::delete('waitlist/{id}',[WaitlistController::class, 'destroy']);
});
