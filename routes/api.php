<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\SocialAuthController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\fronted\ProductsController as FrontedProductsController;
use App\Http\Controllers\NoticationController;
use App\Http\Controllers\UserController;
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
    Route::post("/cart", [CartsController::class, "increaseQuantity"]);
    // remove from cart
    Route::post("/cart/decrease", [CartsController::class, "decreaseQuantity"]);
    // get cart items
    Route::get("/cart", [CartsController::class, "getCartItems"]);
    // add to favorite
    Route::post('/favorites/{productId}', [FavoriteController::class, 'addToFavorites']);
    Route::get('/getAllfavorites', [FavoriteController::class, 'getFavorites']);
    // remove from favorite
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'removeFromFavorites']);
    // get cart
    // get user profile
    Route::get('/profile', [UserController::class, 'profile']);
    // update user profile
    Route::post('/profile', [UserController::class, 'updateProfile']);
    // count products
    Route::get('/count', [FrontedProductsController::class, 'countProduct']);
    // get notifications
    Route::get('/notifications', [NoticationController::class, 'getNotifications']);
});


// authentication JWT
Route::post("/login", [AuthController::class, "login"]);
// Protected routes (JWT required)
Route::middleware(['jwt.auth', 'checkAdmin'])->group(function () {
    Route::apiResource('/category', CategoryController::class);
    Route::apiResource('/product', ProductsController::class);
});


// test api
