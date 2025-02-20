<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use App\Helpers\ApiResponse;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone_number' => 'required|string|max:15',
                'address' => 'required|string|max:255',
                'role_id' => 'required|string|max:255',
            ]);

            // Buat user baru
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone_number' => $validatedData['phone_number'],
                'address' => $validatedData['address'],
                'role_id' => $validatedData['role_id'],
            ]);

            return ApiResponse::success(['user' => $user], 'User registered successfully', 201);
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error', $e->errors(), 422);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to register user', ['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Cari user berdasarkan email
            $user = User::where('email', $validatedData['email'])->first();

            // Periksa password
            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Pastikan `sanctum` digunakan sebelum generate token
            if (!method_exists($user, 'createToken')) {
                return ApiResponse::error(
                    'Personal Access Token feature is not available. Make sure Laravel Sanctum is installed and configured correctly.',
                    [],
                    500
                );
            }

            // Buat token
            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::success([
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 'Login successful');
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error', $e->errors(), 422);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to login', ['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Hapus token yang sedang aktif
            $request->user()->currentAccessToken()->delete();

            return ApiResponse::success([], 'Logged out successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to logout', ['error' => $e->getMessage()], 500);
        }
    }
}
