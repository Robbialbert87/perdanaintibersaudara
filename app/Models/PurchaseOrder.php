<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
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
        'kata_penutup',
        'tampilkan_gambar',
        'selected_images',
        'vendor',
        'vendor_address',
        'vendor_cp',
        'vendor_phone',
        'buyer_name',
        'buyer_address',
        'buyer_cp',
        'buyer_phone',
        'shipping_name',
        'shipping_address',
        'shipping_cp',
        'shipping_phone',
        'discount',
        'ppn',
        'grand_total',
        'total_dp',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'perihal' => 'array',
        'selected_images' => 'array',
        'discount' => 'decimal:2',
        'ppn' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
