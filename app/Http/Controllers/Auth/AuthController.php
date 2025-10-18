<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string|min:8',
            ]);
            if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
                $user = Auth::user();
                $accessToken = $user->createToken('authToken')->accessToken;
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'data' => [
                        'role' => $user->role->role_name,
                        'token' => $accessToken
                    ]
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password',
                'data' => []
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login. Please try again.' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function logout(Request $request)
    {
       try {
            if (Auth::check()) {
                Auth::user()->tokens()->delete();

                return response()->json([
                    'response_code' => 200,
                    'status'        => 'success',
                    'message'       => 'Successfully logged out',
                ]);
            }

            return response()->json([
                'response_code' => 401,
                'status'        => 'error',
                'message'       => 'User not authenticated',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'An error occurred during logout',
            ], 500);
        }
    }
}
