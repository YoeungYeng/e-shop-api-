<?php

namespace App\Http\Controllers\fronted;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    // get all products
    public function getAllProduct(Products $products)
    {
        try {
            $data = $products::orderBy("created_at", "desc")->get();
            // return json succefully
            return response()->json([
                "status" => 200,
                "message" => "all products",
                "data" => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "externall server errors",
                "errors" => $e->getMessage()
            ], 500);
        }
    }
    // last product
    public function lastProduct(Products $products)
    {
        try {
            $data = $products::orderBy('created_at', 'asc')
                ->where('status', 'active')->limit(8)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Last Products',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "external errors",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    // news arrival product
    public function arrivalProduct(Products $products)
    {
        try {
            $data = $products::orderBy('created_at', 'desc')
                ->where('status', 'active')->take(8)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'New arrivals Products',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "external errors",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
