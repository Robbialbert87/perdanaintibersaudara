<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'jenis',
        'tanggal',
        'customer_id',
        'tujuan_nama',
        'tujuan_alamat',
        'perihal',
        'isi',
        'lampiran',
        'nama_penandatangan',
        'jabatan_penandatangan',
        'status',
        'verify_token',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'lampiran' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getJudulAttribute(): string
    {
        return 'SURAT '.strtoupper($this->jenis);
    }
}
