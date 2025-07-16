<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Add a product to favorites
    public function addToFavorites(Request $request, $productId)
    {
        $user = $request->user();  // Get the authenticated user

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if product exists
        $product = Products::find($productId);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Check if already favorited
        if ($user->favoriteProducts()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Product already in favorites'], 400);
        }

        // Add to favorites
        $user->favoriteProducts()->attach($productId);

        return response()->json([
            'message' => 'Product added to favorites',
            'data' => $product
        ], 200);
    }


    public function getFavorites(Request $request)
    {
        $user = $request->user();  // Get the authenticated user
        // Get all favorite products
        $favorites = $user->favoriteProducts()->get();
        return response()->json(['favorites' => $favorites], 200);
    }

    // Remove a product from favorites
    public function removeFromFavorites(Request $request, $productId)
    {
        $user = $request->user();  // Get the authenticated user
        // Check if already favorited
        if (!$user->favoriteProducts()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Product not in favorites'], 400);
        }
        // Remove from favorites
        $user->favoriteProducts()->detach($productId);
        return response()->json([
            'message' => 'Product removed from favorites',
            'data' => $productId
        ], 200);
    }

}
