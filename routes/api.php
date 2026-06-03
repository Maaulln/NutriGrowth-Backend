<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\AnalysisController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\NotificationController;

// ── Autentikasi publik (tidak perlu token) ────────────────────────────────────
// POST /api/auth/register  → daftar akun baru, mengembalikan token
// POST /api/auth/login     → masuk dengan email & password, mengembalikan token
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ── Route yang memerlukan token (Authorization: Bearer <token>) ───────────────
Route::middleware('auth:sanctum')->group(function () {

    // POST /api/auth/logout  → hapus token aktif (keluar dari aplikasi)
    // GET  /api/auth/me      → ambil data profil user yang sedang login
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // POST /api/analyze       → analisis gizi anak (stunting + rekomendasi makanan dari DB)
    // GET  /api/analyses      → riwayat analisis user (opsional: ?child_id=X)
    Route::post('analyze', [AnalysisController::class, 'analyze']);
    Route::get('analyses',  [AnalysisController::class, 'index']);

    // GET   /api/notifications          → daftar notifikasi user (+ unread_count)
    // PATCH /api/notifications/read-all → tandai semua sudah dibaca
    Route::get('notifications',           [NotificationController::class, 'index']);
    Route::patch('notifications/read-all', [NotificationController::class, 'readAll']);

    // CRUD data anak — hanya bisa mengakses anak milik sendiri
    // GET    /api/children                        → daftar semua anak milik user
    // POST   /api/children                        → tambah anak baru
    // GET    /api/children/{id}                   → detail satu anak
    // PUT    /api/children/{id}                   → update data anak
    // DELETE /api/children/{id}                   → hapus data anak
    // GET    /api/children/{id}/assessments        → riwayat assessment untuk anak
    // GET    /api/children/{id}/growth-records     → riwayat tumbuh kembang anak
    Route::apiResource('children', ChildController::class);
    Route::get('children/{childId}/assessments',    [AnalysisController::class, 'childAssessments']);
    Route::get('children/{childId}/growth-records', [ChildController::class, 'growthRecords']);
});

// ── Route admin (memerlukan token) ───────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // CRUD manajemen user (untuk admin)
    // GET    /api/users          → daftar semua user
    // POST   /api/users          → tambah user baru
    // GET    /api/users/{id}     → detail satu user
    // PUT    /api/users/{id}     → update data user
    // DELETE /api/users/{id}     → hapus user
    Route::apiResource('users', UserController::class);

    // GET /api/admin/children              → semua data anak dari seluruh user (beserta info orang tua)
    // GET /api/admin/users/{id}/children   → semua anak milik user tertentu
    Route::get('admin/children',                    [\App\Http\Controllers\Api\AdminChildController::class, 'index']);
    Route::get('admin/users/{id}/children',         [\App\Http\Controllers\Api\AdminChildController::class, 'byUser']);
});

// ── Data makanan (publik, tidak perlu token) ──────────────────────────────────
// GET /api/foods              → daftar semua makanan (bisa filter: ?category= atau ?search=)
// GET /api/foods/{id}         → detail satu makanan beserta informasi nutrisi
Route::get('foods', [FoodController::class, 'index']);
Route::get('foods/{id}', [FoodController::class, 'show']);

// ── CRUD makanan (admin only, perlu token) ────────────────────────────────────
// POST   /api/admin/foods        → tambah makanan baru
// PUT    /api/admin/foods/{id}   → update makanan
// DELETE /api/admin/foods/{id}   → hapus makanan
Route::middleware('auth:sanctum')->group(function () {
    Route::post('admin/foods',         [FoodController::class, 'store']);
    Route::put('admin/foods/{id}',     [FoodController::class, 'update']);
    Route::delete('admin/foods/{id}',  [FoodController::class, 'destroy']);
});

