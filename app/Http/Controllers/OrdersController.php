<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Models\OrdersItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function saveorder(Request $request)
    {
        if (!empty($request->cart)) {
            // save order
            $order = new Orders();
            $order->name = $request->name;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->user_id = $request->user_id;
            $order->status = 'pending';
            $order->subTotal = $request->subTotal;
            $order->grand_total = $request->grand_total;
            $order->shipping = $request->shipping;
            $order->discount = $request->discount;
            $order->payment_status = $request->payment_status;
            // save to database
            $order->save();
            foreach ($request->cart as $item) {
                if (!isset($item['product_id'])) {
                    continue; // or return an error response
                }
                // save order items
            $orderItem = new OrdersItems();
                $orderItem->order_id = $order->id;
                $orderItem->name = $item['name']; // Ensure name is set
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = $item['price'];
                $orderItem->price = $item['quantity'] * $item['price'];
                $orderItem->save();

                return response()->json([
                    "status" => 200,
                    "message" => "You have succefully place order ",
                    "data" => $order,
                    "order_items" => $order->item,
                ], 200);

            }

        } else {
            return response()->json([
                "status" => 400,
                "message" => "Your cart is empty",
                "data" => null,
                "order_items" => null,
            ], 400);
        }
    }
}
