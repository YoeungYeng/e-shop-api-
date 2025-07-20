<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    // get information user on profile page
    public function profile(Request $request)
    {
        try {
            $user = Auth::user(); // or auth()->user();
            
            return response()->json([
                'status' => 200,
                'message' => 'User profile fetched successfully',
                'user' => $user,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch user profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // update user profile
    public function updateProfile(StoreUserRequest $storeUserRequest){
        try {
            // Get authenticated user
            $user = JWTAuth::user();

            // Validate and update user data
            $validatedData = $storeUserRequest->validated();
            $user->update($validatedData);

            // Handle image upload if provided
            if ($storeUserRequest->hasFile('image')) {
                $imageUrl = $storeUserRequest->uploadImage($storeUserRequest->file('image'));
                $user->image = $imageUrl;
                $user->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Profile updated successfully',
                'user' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Something went wrong!'
            ], 500);
        }
    }

}
