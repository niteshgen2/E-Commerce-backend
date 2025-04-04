<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class AuthController extends Controller
{
    // Show the admin login form
    public function showLoginForm()
    {
        return view('admin.auth.login'); // Ensure this view exists
    }

    // Admin Login using JWT
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Validate user and password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Check if user has admin role
        if ($user->role !== 'admin') {
            return back()->withErrors(['email' => 'Access denied! Only admins can log in']);
        }

        // Generate JWT Token
        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return back()->withErrors(['email' => 'Could not generate token']);
        }

        // Store admin session
        session(['admin_token' => $token, 'admin_user' => $user]);

        return redirect()->route('admin.dashboard');
    }

    // Admin Dashboard
    public function dashboard()
    {
        if (!session()->has('admin_token')) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Unauthorized access']);
        }

        return view('admin.auth.dashboard', ['user' => session('admin_user')]);
    }

    // Admin Logout (Invalidate JWT Token)
    public function logout(Request $request)
{
    try {
        $token = $request->bearerToken(); // Get JWT token from header
        
        if ($token) {
            JWTAuth::invalidate($token); // Invalidate the token
            return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
        } else {
            return redirect()->route('admin.login')->with('error', 'No token found.');
        }
    } catch (JWTException $e) {
        return redirect()->route('admin.login')->with('error', 'Failed to logout.');
    }
}

}
