<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Orders extends Model
{
    /** @use HasFactory<\Database\Factories\OrdersFactory> */
    use HasFactory, HasApiTokens;
    protected $table = "orders";
    protected $fillable = [
        'user_id',
        'status',
        'subTotal',
        'grand_total',
        'shipping',
        'discount',
        'payment_status',
        'email',
        'name',
        'mobile',
        'address',
        'city',
        'zip',
        'created_at',
        'updated_at'
    ];
    // user integration with orders
    public function users(){
        return $this->belongsTo(User::class);
    }

    public function item(){
        return $this->hasMany(OrdersItems::class);
    }
}
