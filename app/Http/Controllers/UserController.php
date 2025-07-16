<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

}
