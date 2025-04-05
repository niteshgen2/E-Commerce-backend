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
    //Register Admin or User
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

    //Show Admin Login Form
    public function showLoginForm()
    {
        return view('admin.auth.login'); // View: resources/views/admin/auth/login.blade.php
    }

    //Login Admin or User
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
            }
        } catch (JWTException $e) {
            return back()->withErrors(['email' => 'Could not create token'])->withInput();
        }

        $user = auth()->user();

        // Check if the request is from admin login route
        if ($request->is('admin/login') && $user->role !== 'admin') {
            return back()->withErrors(['email' => 'Access denied! Only admins can log in here.'])->withInput();
        }

        // Store the admin user in the session
        session(['admin_user' => $user]);

        // Redirect to the admin dashboard
        return redirect()->route('admin.dashboard');
    }

    // Admin Dashboard View
    public function dashboard()
    {
        // You can add extra checks here if needed
        if (!session()->has('admin_user')) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Please login first.']);
        }

        return view('admin.auth.dashboard'); // View: resources/views/admin/auth/dashboard.blade.php
    }

    // Logout Admin (Invalidate Token and Session)
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            session()->forget('admin_user');
            return redirect()->route('admin.login')->with('message', 'Logged out successfully.');
        } catch (JWTException $e) {
            return back()->withErrors(['error' => 'Failed to logout']);
        }
    }

    // API method to store session manually if needed
    public function storeSession(Request $request)
    {
        $user = (object) $request->user;
        session(['admin_user' => $user]);

        return response()->json(['message' => 'Session stored']);
    }

    public function create()
{
    if (!session()->has('admin_user')) {
        return redirect()->route('admin.login')->withErrors(['email' => 'Please login first.']);
    }
    
    return view('admin.product.add_product'); 
}
}
