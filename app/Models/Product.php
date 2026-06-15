<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'images',
        'videos',
        'active_images',
        'active_videos',
        'spesifikasi',
        'satuan',
        'harga_default',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'active_images' => 'array',
        'active_videos' => 'array',
    ];
}
