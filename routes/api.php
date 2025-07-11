<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\fronted\ProductsController as FrontedProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/category', CategoryController::class);
Route::apiResource('/product', ProductsController::class);

//// for fronted
Route::get("/getAllProduct", [FrontedProductsController::class, "getAllProduct"]);
// last product
Route::get("/lastproduct", [FrontedProductsController::class, 'lastProduct']);
// new arrival product
Route::get("/newproduct", [FrontedProductsController::class, 'arrivalProduct']);