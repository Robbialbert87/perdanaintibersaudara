<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'tanggal',
        'customer_id',
        'perihal',
        'perihal_surat',
        'total',
        'status',
        'verify_token',
        'catatan',
        'kata_pengantar',
        'tampilkan_gambar',
        'selected_images',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'perihal' => 'array',
        'selected_images' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
