<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductsRequest;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Products $products)
    {
        try {
            $data = $products::orderBy('created_at', 'desc')->get();

            // return succefully
            return response()->json([
                "status" => 200,
                "message" => "all products",
                "data" => $data
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "external server error"
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductsRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile("image")) {
                $data["image"] = $request->uploadImage($request->file("image"));
            }

            $new_products = Products::create($data);

            return response()->json([
                "status" => 201,
                "message" => "add product succes",
                "data" => $new_products
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "server external error",
                "error" => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductsRequest $request, $id)
    {
        try {
            $product = Products::findOrFail($id);
            // get the validated data from the request
            $data = $request->validated();
            // Upload image if provided
            if ($request->hasFile('image')) {
                $data['image'] = $request->uploadImage($request->file('image'));
            }

            $product->update($data);
            return response()->json([
                "status" => 201,
                "message" => "updated products success",
                "data" => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "external server error",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $products)
    {
        try {

            $products->delete();
            return response()->json([
                'status' => 200,
                'message' => 'News article deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the products article',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
