<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Password;

use App\Models\ProductFilter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuthController extends Controller
{
    // Register user or admin
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|unique:users',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'role' => 'required|in:admin,user',
            'birthday' => 'nullable|date',
            'phone_number' => 'nullable|string',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], 201);
    }

    // Login (Updated for Admin)
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();

    // Ensure the user logging in is an admin
    if ($user->role !== 'admin') {
        return response()->json(['message' => 'Access denied. Only admins can log in here.'], 403);
    }

    return response()->json([
        'message' => 'Admin login successful',
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => JWTAuth::factory()->getTTL() * 60,
        'user' => $user
    ]);
}


    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out successfully']);
    }

    // Forgot Password
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return response()->json(['message' => __($status)]);
    }
}

class ProductFilterController extends Controller
{
    use AuthorizesRequests;

    // Get Filters
    public function index()
    {
        $this->authorize('viewAny', ProductFilter::class);
        return response()->json(['success' => true, 'data' => ProductFilter::all()], 200);
    }
}

class UserController extends Controller
{
    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'username' => 'sometimes|string|unique:users,username,' . $id,
            'email' => 'sometimes|string|email|unique:users,email,' . $id,
            'first_name' => 'sometimes|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'sometimes|string',
            'role' => 'sometimes|in:admin,user',
            'birthday' => 'nullable|date',
            'phone_number' => 'nullable|string',
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
