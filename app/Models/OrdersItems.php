<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class OrdersItems extends Model
{
    /** @use HasFactory<\Database\Factories\OrdersItemsFactory> */
    use HasFactory, HasApiTokens;
    protected $table = "orders_items";
    // products
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price'
    ];
    public function products()
    {
        return $this->hasMany(Products::class);
    }
    // order
    public function orders()
    {
        return $this->hasMany(Orders::class);
    }
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime: d M Y',
            'updated_at' => 'datetime: d M Y',
        ];
    }
}
