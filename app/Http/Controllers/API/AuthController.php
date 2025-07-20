<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // login for admin
    public function login(Request $request)
    {
        try {
            // Validate login credentials
            $validate = Validator::make($request->all(), [
                "email" => "required|email",
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',      // At least one lowercase letter
                    'regex:/[A-Z]/',      // At least one uppercase letter
                    'regex:/[0-9]/',      // At least one number
                    'regex:/[@$!%*#?&]/', // At least one special character
                ],
            ]);

            if ($validate->fails()) {
                return response()->json([
                    "status" => 400,
                    "message" => "Bad request",
                    "error" => $validate->errors()
                ], 400);
            }

            // Attempt to log in using JWT
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Get authenticated user
            $user = JWTAuth::user();

            // Optional: restrict login to admin only
            if ($user->role !== 'admin') {
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied. Admins only.'
                ], 403);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
            ]);

        } catch (JWTException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Could not create token'
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "Server internal error",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    // login for user
    // register for user
    public function register(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',      // At least one lowercase letter
                    'regex:/[A-Z]/',      // At least one uppercase letter
                    'regex:/[0-9]/',      // At least one number
                    'regex:/[@$!%*#?&]/', // At least one special character
                ],
            ]);

            // Create the user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Generate a JWT token for the user
            $token = JWTAuth::fromUser($user);

            // Return a successful response
            return response()->json([
                'status' => 201,
                'user' => $user,
                // 'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'errors' => $e->errors()
            ], 422);

        } catch (JWTException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Could not create token'
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Something went wrong! 💔'
            ], 500);
        }
    }

    public function loginClient(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',      // At least one lowercase letter
                    'regex:/[A-Z]/',      // At least one uppercase letter
                    'regex:/[0-9]/',      // At least one number
                    'regex:/[@$!%*#?&]/', // At least one special character
                ],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors()
                ], 400);
            }

            // Try to generate token
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Get authenticated user
            $user = JWTAuth::user();

            // Check if user is clients
            if ($user->role !== 'customer') {
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied. Clients only.'
                ], 403);
            }

            return response()->json([
                'status' => 200,
                'token' => $token,
                'id' => $user->id,
                'name' => $user->name
            ], 200);

        } catch (JWTException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Could not create token'
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Something went wrong!'
            ], 500);
        }
    }

    
}
