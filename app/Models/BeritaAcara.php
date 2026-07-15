<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'tanggal',
        'kegiatan',
        'lokasi',
        'pihak_penyerah_nama',
        'pihak_penyerah_alamat',
        'pihak_penerima_nama',
        'pihak_penerima_alamat',
        'closing_text',
        'status',
        'verify_token',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(BeritaAcaraItem::class);
    }
}
