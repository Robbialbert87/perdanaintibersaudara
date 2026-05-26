<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
