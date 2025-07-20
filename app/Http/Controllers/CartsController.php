<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Http\Requests\StoreCartsRequest;
use App\Http\Requests\UpdateCartsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartsController extends Controller
{
    public function increaseQuantity(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $cart = Carts::firstOrNew([
        'user_id' => $validated['user_id'],
        'product_id' => $validated['product_id'],
    ]);

    // If the cart item already exists, increment the quantity
    if ($cart->exists) {
        $cart->quantity += $validated['quantity'];
    } else {
        $cart->quantity = $validated['quantity'];
    }

    $cart->save();

    return response()->json([
        'message' => $cart->wasRecentlyCreated ? 'Item added to cart' : 'Cart updated',
        'cart' => $cart
    ], 200);
}



    // remove from cart
    public function decreaseQuantity(Request $request)
{
    $cartItem = Carts::where('product_id', $request->product_id)
                     ->where('user_id', $request->user_id)
                     ->first();

    if (!$cartItem) {
        return response()->json(['message' => 'Cart item not found'], 404);
    }

    // Decrease quantity
    $cartItem->quantity -= $request->quantity;

    if ($cartItem->quantity <= 0) {
        $cartItem->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }

    $cartItem->save();
    return response()->json(['message' => 'Cart quantity decreased']);
}




    // get all to cart
    public function getCartItems(Request $request)
    {
        $userId = $request->user()->id;

        $cartItems = Carts::where('user_id', $userId)->with('product')->get();

        return response()->json([
            'message' => 'Cart items retrieved successfully',
            'data' => $cartItems
        ]);
    }

}
