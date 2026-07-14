<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_kartu',
        'tanggal',
        'customer_id',
        'nama_alat',
        'type_alat',
        'nama_rs_klinik',
        'tgl_instalasi',
        'catatan',
        'verifikator',
        'ttd_pembeli',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tgl_instalasi' => 'date',
        'ttd_pembeli' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
