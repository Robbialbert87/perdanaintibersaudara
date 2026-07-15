<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'berita_acara_id',
        'nama_produk',
        'quantity',
        'berfungsi',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'berfungsi' => 'boolean',
    ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class);
    }
}
