<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Products extends Model
{
    /** @use HasFactory<\Database\Factories\ProductsFactory> */
    use HasFactory, HasApiTokens;
    
    // table 
    protected $table = "products";
    protected $fillable = [
        "name",
        "price",
        "image",
        "stock",
        "status",
        "category_id",
        
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime: d M Y',
            'updated_at' => 'datetime: d M Y',
        ];
    }

    // Configure the image upload settings
    

}
