<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'features',
        'images',
        'videos',
        'active_images',
        'active_videos',
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'videos' => 'array',
        'active_images' => 'array',
        'active_videos' => 'array',
    ];
}
