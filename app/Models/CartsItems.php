<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CartsItems extends Model
{
    /** @use HasFactory<\Database\Factories\CartsItemsFactory> */
    use HasFactory, HasApiTokens;
    // name table in database;
    protected $table = "carts_items";
    protected $fillable = [
        "products_id", "carts_id", "quality"
    ];

    public function products(){
        return $this->hasMany(Products::class);
    }
    // cart
    public function carts(){
        return $this->hasMany(Carts::class);
    }
}
