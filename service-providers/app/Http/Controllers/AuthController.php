<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        // If user is already authenticated, redirect to manual
        if (request()->session()->has('authenticated')) {
            return redirect('/manual');
        }
        
        return view('login');
    }
    
    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $credentials = $request->only('username', 'password');
        
        // For demo purposes, you can hardcode a user
        // In production, you should use a proper user table
        if ($credentials['username'] === 'admin' && $credentials['password'] === 'password123') {
            // Create a simple session-based authentication
            $request->session()->put('authenticated', true);
            $request->session()->put('user', [
                'id' => 1,
                'username' => 'admin',
                'name' => 'Administrator'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => Str::random(60),
                'user' => [
                    'id' => 1,
                    'username' => 'admin',
                    'name' => 'Administrator'
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }
    
    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['authenticated', 'user']);
        
        // Always return JSON response for API calls
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
    
    /**
     * Check authentication status
     */
    public function checkAuth(Request $request)
    {
        if ($request->session()->has('authenticated')) {
            return response()->json([
                'success' => true,
                'authenticated' => true,
                'user' => $request->session()->get('user')
            ]);
        }
        
        return response()->json([
            'success' => false,
            'authenticated' => false
        ]);
    }
}
