<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    protected static $services = [
        [
            'title' => 'Instalasi & Komisioning Alat Medis',
            'description' => 'Tim teknisi bersertifikat kami melakukan instalasi profesional, pengujian fungsi, dan komisioning lengkap untuk semua jenis alat medis. Kami memastikan setiap perangkat terpasang sesuai standar pabrik dan siap operasional. Termasuk pelatihan pengguna dan dokumentasi teknis lengkap.',
            'image' => 'services/e2qUTQw92WvR1NTjLFbx7qEUjJMRfnp4KSBw08HH.jpg',
            'features' => [
                'Instalasi sesuai standar pabrik dan ISO 13485',
                'Uji fungsi dan kalibrasi awal (IQ/OQ)',
                'Pelatihan operator dan maintenance dasar',
                'Dokumentasi teknis dan serah terima lengkap',
                'Garansi instalasi 6 bulan',
            ],
        ],
        [
            'title' => 'Perbaikan & Maintenance Alat Medis',
            'description' => 'Layanan perbaikan dan perawatan berkala untuk semua jenis peralatan medis. Didukung teknisi ahli dengan pengalaman puluhan tahun dan suku cadang original. Tersedia kontrak maintenance tahunan dengan respon time 1x24 jam untuk area Jambi dan sekitarnya.',
            'image' => 'services/ntddMWCo39LnoLwvTuQCVg9nX1WLhyx6SEHeZA47.jpg',
            'features' => [
                'Service call dalam 1x24 jam (area Jambi)',
                'Maintenance preventif terjadwal bulanan/tahunan',
                'Suku cadang original terjamin',
                'Laporan maintenance dan rekomendasi perbaikan',
                'Diskon 20% untuk pemegang kontrak tahunan',
            ],
        ],
        [
            'title' => 'Kalibrasi Alat Medis',
            'description' => 'Layanan kalibrasi traceable untuk alat medis dengan sertifikat resmi. Kami mengkalibrasi berbagai alat seperti defibrillator, ECG, infusion pump, ventilator, dan patient monitor. Menjamin akurasi pengukuran sesuai standar nasional dan internasional.',
            'image' => 'services/QI9E9YX0BfYU7UM1IhyuHFiVfpDt6kbTp7h46Vbc.jpg',
            'features' => [
                'Kalibrasi traceable ke standar nasional (SNI)',
                'Sertifikat kalibrasi resmi dan terakreditasi',
                'Cakupan: ECG, Defibrillator, Infusion Pump, Ventilator, Monitor',
                'Jadwal kalibrasi rutin (6 bulan / 1 tahun)',
                'Rekonsiliasi dan catatan riwayat kalibrasi',
            ],
        ],
        [
            'title' => 'Pengadaan Rumah Sakit & Klinik',
            'description' => 'Solusi pengadaan alat kesehatan lengkap untuk rumah sakit, klinik, puskesmas, dan laboratorium. Kami menyediakan produk dari merek terpercaya dengan harga kompetitif. Didukung konsultasi kebutuhan, pengiriman, instalasi, dan after-sales service.',
            'image' => 'services/5spXTLWUUCQfPfGEuOV7ZSVxaiu7kIiWLzAOqma3.jpg',
            'features' => [
                'Konsultasi kebutuhan alat medis gratis',
                'Supplier resmi merek nasional & internasional',
                'Harga kompetitif dengan garansi resmi',
                'Pengiriman ke seluruh Indonesia (khusus Jambi free ongkir)',
                'After-sales service & dukungan teknis',
            ],
        ],
        [
            'title' => 'Konstruksi & Renovasi Gedung Kesehatan',
            'description' => 'Layanan pembangunan dan renovasi gedung fasilitas kesehatan mulai dari klinik pratama hingga rumah sakit. Termasuk perencanaan layout ruangan sesuai standar akreditasi, instalasi utilitas medis (O2, N2O, suction), dan sistem grounding ruang operasi.',
            'image' => 'services/e2qUTQw92WvR1NTjLFbx7qEUjJMRfnp4KSBw08HH.jpg',
            'features' => [
                'Perencanaan layout sesuai standar akreditasi RS',
                'Instalasi gas medis (O2, N2O, Vacuum, Compressed Air)',
                'Sistem grounding dan kelistrikan medis',
                'Renovasi ruang operasi, ICU, Radiologi, Lab',
                'Sertifikat kelayakan bangunan dan utilitas',
            ],
        ],
    ];

    public function definition(): array
    {
        $service = $this->faker->unique()->randomElement(static::$services);

        return $service;
    }
}
