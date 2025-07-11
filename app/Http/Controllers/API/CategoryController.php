<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{
    // index
    public function index(Category $category)
    {
        try {
             $data = $category::orderBy('created_at', 'desc')->get();
            
            // return succefully
            return response()->json([
                "status" => 200,
                "message" => "all category",
                "data" => $data
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "external server error"
            ], 500);
        }
    }
    // method for store data to database
    public function store(StoreCategoryRequest $request)
    {
        try {
            // validate data
            $data = $request->validated();
            // save data to data
            $category = Category::create($data);
            // return json
            return response()->json([
                "status" => 201,
                "message" => "create category succeful",
                "data" => $category
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "server exteral error",
                "error" => $e->getMessage()
            ], 500);
        }
    }
    // update category
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {

            $update_category = Category::findOrFail($id);
            $data = $request->validated();
            $update_category->update($data);
            return response()->json([
                "status" => 200,
                "message" => "category updated",
                "data" => $update_category
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "external error",
                "error" => $e->getMessage()
            ], 500);
        }
    }
    // destroy
    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            "status" => 200,
            "message" => "category delete fully",
            "data" => $category
        ], 200);
    }

}
