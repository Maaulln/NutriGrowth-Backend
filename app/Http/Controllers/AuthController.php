<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
        * POST /api/auth/register
        * Menerima data nama, email, dan password dari request.
        * Jika valid, method ini membuat user baru di database dan mengembalikan token Sanctum.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('nutriGrowth-app')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil!',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /**
        * POST /api/auth/login
        * Menerima email dan password dari request.
        * Jika kredensial valid, method ini mengambil user dari database dan mengembalikan token Sanctum.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'error' => 'Email atau password salah.',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        // Hapus token lama agar tidak menumpuk
        $user->tokens()->delete();

        $token = $user->createToken('nutriGrowth-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
        * POST /api/auth/logout
        * Menghapus token akses aktif milik user yang sedang login.
        * Method ini hanya berjalan jika request sudah terautentikasi dengan auth:sanctum.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
        * GET /api/auth/me
        * Mengembalikan data user yang sedang login.
        * Data diambil dari request user yang sudah terautentikasi dengan auth:sanctum.
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => [
                'id'    => $request->user()->id,
                'name'  => $request->user()->name,
                'email' => $request->user()->email,
                'role'  => $request->user()->role,
            ],
        ]);
    }
}
