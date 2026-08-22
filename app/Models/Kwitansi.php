<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kwitansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_kwitansi',
        'tanggal',
        'customer_id',
        'invoice_id',
        'jumlah',
        'untuk_pembayaran',
        'catatan',
        'verify_token',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Kwitansi $kwitansi) {
            if (empty($kwitansi->verify_token)) {
                $kwitansi->verify_token = (string) Str::uuid();
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
