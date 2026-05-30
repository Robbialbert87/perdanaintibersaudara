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
        'spesifikasi',
        'satuan',
        'harga_default'
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
