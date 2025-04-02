<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class AuthController extends Controller
{
    // 🔹 Admin Login Function
    public function adminLogin(Request $request)
    {
        // Validate Input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 🔹 Attempt to authenticate using JWT
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // 🔹 Get the authenticated user from JWT
        $user = JWTAuth::user();

        // 🔹 Ensure the user is an admin
        if ($user->role !== 'admin') {
            // Invalidate Token if the user is not admin
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        // 🔹 Return Success Response with JWT Token
        return response()->json([
            'message' => 'Admin login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'email' => $user->email,
            ]
        ]);
    }

    // 🔹 Admin Logout Function
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout'], 500);
        }
    }
}
