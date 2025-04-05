<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class AuthController extends Controller
{
    // ✅ Register Admin or User
    public function register(Request $request)
    {
        $request->validate([
            'username'      => 'required|string|unique:users',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|string|min:6',
            'first_name'    => 'required|string',
            'middle_name'   => 'nullable|string',
            'last_name'     => 'required|string',
            'birthday'      => 'nullable|date',
            'phone_number'  => 'nullable|string',
            'role'          => 'required|in:admin,user', // allow only admin or user
        ]);

        $user = User::create([
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'first_name'    => $request->first_name,
            'middle_name'   => $request->middle_name,
            'last_name'     => $request->last_name,
            'birthday'      => $request->birthday,
            'phone_number'  => $request->phone_number,
            'role'          => $request->role,
        ]);

        try {
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'token'   => $token,
                'user'    => $user
            ], 201);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not generate token'
            ], 500);
        }
    }

    // ✅ Login Admin or User
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        $user = auth()->user();

        // If login from Admin panel, you can check role
        if ($request->is('api/admin/*') && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied! Only admins can log in here.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => $user
        ]);
    }

    // ✅ Get Authenticated User
    public function me()
    {
        try {
            $user = auth()->user();
            return response()->json([
                'success' => true,
                'user'    => $user
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalid or expired'
            ], 401);
        }
    }

    // ✅ Logout User (Invalidate Token)
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout'
            ], 500);
        }
    }

    // Show Admin Login Form
public function showLoginForm()
{
    return view('admin.auth.login'); // Make sure this Blade file exists: resources/views/admin/login.blade.php
}

public function storeSession(Request $request)
{
    $user = (object) $request->user;
    session(['admin_user' => $user]);

    return response()->json(['message' => 'Session stored']);
}

}
