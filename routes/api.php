<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\SocialAuthController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\fronted\ProductsController as FrontedProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// register for client
Route::post("/register", [AuthController::class, "register"]);
// login for client
Route::post("/loginclient", [AuthController::class, "loginClient"]);
//// for fronted
Route::middleware(['jwt.auth', 'checkUser'])->group(function () {
    Route::get("/getAllProduct", [FrontedProductsController::class, "getAllProduct"]);
    // last product
    Route::get("/lastproduct", [FrontedProductsController::class, 'lastProduct']);
    // new arrival product
    Route::get("/newproduct", [FrontedProductsController::class, 'arrivalProduct']);
    // get all category
    Route::get("getcategory", [CategoryController::class, 'getCategory']);
    // add to cart
    Route::post("/cart", [CartsController::class, "addToCart"]);
    // add to favorite
    Route::post('/favorites/{productId}', [FavoriteController::class, 'addToFavorites']);
    Route::get('/getAllfavorites', [FavoriteController::class, 'getFavorites']);

});

// authentication JWT
Route::post("/login", [AuthController::class, "login"]);
// Protected routes (JWT required)
Route::middleware(['jwt.auth', 'checkAdmin'])->group(function () {
    Route::apiResource('/category', CategoryController::class);
    Route::apiResource('/product', ProductsController::class);
});


// test api
