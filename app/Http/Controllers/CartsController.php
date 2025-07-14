<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Http\Requests\StoreCartsRequest;
use App\Http\Requests\UpdateCartsRequest;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check for existing item
        $cartItem = Carts::where('user_id', $data['user_id'])
            ->where('product_id', $data['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            $cartItem = Carts::create($data);
        }

        return response()->json([
            'message' => 'Item added to cart successfully',
            'data' => $cartItem
        ], 201);
    }

}
