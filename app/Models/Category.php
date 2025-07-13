<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, HasApiTokens;
    // table category
    protected $table = "categories";
    protected $fillable = ['name', 'status', 'image'];

    // related to proudct
    public function products(){
        return $this->hasMany(Products::class);
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime: d M Y',
            'updated_at' => 'datetime: d M Y',
        ];
    }
}
