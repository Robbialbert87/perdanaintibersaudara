<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'images',
        'videos',
        'active_images',
        'active_videos',
        'date',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'active_images' => 'array',
        'active_videos' => 'array',
        'date' => 'date',
    ];
}
