<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'nomor_surat',
        'tanggal',
        'customer_id',
        'perihal',
        'total',
        'status',
        'catatan'
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
