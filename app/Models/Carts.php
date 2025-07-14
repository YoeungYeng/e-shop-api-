<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Carts extends Model
{
    /** @use HasFactory<\Database\Factories\CartsFactory> */
    use HasFactory, HasApiTokens;
    protected $table = "carts";
    protected $fillable = ["user_id", "product_id", "quantity"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
