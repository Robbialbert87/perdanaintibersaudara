<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_invoice',
        'tanggal',
        'tanggal_bayar',
        'customer_id',
        'quotation_id',
        'perihal',
        'total',
        'ppn_active',
        'status',
        'catatan',
        'kata_penutup',
        'bukti_bayar',
        'verify_token',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_bayar' => 'date',
        'perihal' => 'array',
        'ppn_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->verify_token)) {
                $invoice->verify_token = (string) Str::uuid();
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
