<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /api/notifications
     * Ambil semua notifikasi milik user yang login (terbaru di atas, limit 30).
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = AppNotification::where('user_id', $request->user()->id)
            ->latest()
            ->limit(30)
            ->get(['id', 'type', 'title', 'message', 'is_read', 'created_at']);

        $unreadCount = AppNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'status'       => 'success',
            'unread_count' => $unreadCount,
            'data'         => $notifications,
        ]);
    }

    /**
     * PATCH /api/notifications/read-all
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function readAll(Request $request): JsonResponse
    {
        AppNotification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }
}
