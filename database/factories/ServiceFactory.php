<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    protected static $services = [
        [
            'title' => 'Timbal (Radiation Shielding)',
            'description' => 'Penyediaan dan pemasangan ruangan timbal (lead shielding) untuk perlindungan radiasi di fasilitas radiologi. Termasuk dinding timbal, kaca Pb, pintu timbal, dan ventilasi sesuai standar keselamatan radiasi BAPETEN.',
            'features' => [
                'Dinding timbal dengan ketebalan sesuai kebutuhan (1-3 mm Pb)',
                'Kaca Pb untuk jendela observasi',
                'Pintu timbal otomatis & manual',
                'Ventilasi dan instalasi elektrik shielding',
                'Sertifikat uji kebocoran radiasi',
            ],
        ],
        [
            'title' => 'Paparan (Uji Paparan Radiasi)',
            'description' => 'Layanan pengukuran dan uji paparan radiasi untuk memastikan keamanan pasien, operator, dan lingkungan. Dilengkapi alat ukur terkalibrasi dan tenaga ahli fisika medis bersertifikat.',
            'features' => [
                'Pengukuran paparan radiasi di area kerja',
                'Uji kebocoran pesawat sinar-X',
                'Pengukuran dosis personel (film badge / TLD)',
                'Laporan hasil uji sesuai standar BAPETEN',
                'Rekomendasi perbaikan jika melebihi ambang batas',
            ],
        ],
        [
            'title' => 'Uji Kesesuaian (Compliance Test)',
            'description' => 'Pengujian kesesuaian pesawat sinar-X dan alat radiologi lainnya sesuai standar nasional dan internasional. Memastikan alat memenuhi persyaratan teknis dan keselamatan sebelum dioperasikan.',
            'features' => [
                'Uji kesesuaian pesawat sinar-X diagnostik',
                'Pengujian kualitas citra dan dosis radiasi',
                'Uji fungsi keselamatan (collimator, filter, KV, mA)',
                'Sertifikat uji kesesuaian resmi',
                'Rekomendasi tindak lanjut jika tidak sesuai',
            ],
        ],
        [
            'title' => 'Perizinan BAPETEN',
            'description' => 'Layanan pengurusan perizinan BAPETEN (Badan Pengawas Tenaga Nuklir) untuk pesawat sinar-X dan alat radiologi. Termasuk konsultasi, penyiapan dokumen, pendampingan inspeksi, hingga penerbitan izin operasi.',
            'features' => [
                'Konsultasi persyaratan perizinan BAPETEN',
                'Penyiapan dokumen teknis dan administratif',
                'Pendampingan inspeksi dan verifikasi lapangan',
                'Pengurusan izin operasi pesawat sinar-X',
                'Perpanjangan izin berkala',
            ],
        ],
        [
            'title' => 'Kalibrasi Alat Medis',
            'description' => 'Layanan kalibrasi untuk alat medis dengan sertifikat resmi dan traceable ke standar nasional. Mencakup kalibrasi pesawat sinar-X, defibrillator, ECG, infusion pump, ventilator, dan patient monitor.',
            'features' => [
                'Kalibrasi traceable ke standar nasional (SNI)',
                'Sertifikat kalibrasi resmi terakreditasi',
                'Cakupan: X-Ray, ECG, Defibrillator, Infusion Pump, Ventilator, Monitor',
                'Jadwal kalibrasi rutin (6 bulan / 1 tahun)',
                'Rekonsiliasi dan catatan riwayat kalibrasi',
            ],
        ],
        [
            'title' => 'Sinkronisasi Satu Sehat',
            'description' => 'Layanan integrasi dan sinkronisasi data alat medis ke platform Satu Sehat (sebelumnya SATUSEHAT). Memastikan data hasil pemeriksaan terkirim otomatis ke sistem kesehatan nasional.',
            'features' => [
                'Integrasi alat ke platform Satu Sehat',
                'Sinkronisasi data hasil pemeriksaan otomatis',
                'Konfigurasi HL7 FHIR dan DICOM',
                'Uji coba dan validasi pengiriman data',
                'Dukungan teknis dan maintenance koneksi',
            ],
        ],
        [
            'title' => 'Install Aplikasi DR/CR',
            'description' => 'Layanan instalasi, konfigurasi, dan pelatihan aplikasi untuk sistem Digital Radiography (DR) dan Computed Radiography (CR). Termasuk setup workstation, DICOM configuration, dan integrasi dengan PACS/RIS.',
            'features' => [
                'Instalasi aplikasi DR/CR di workstation',
                'Konfigurasi DICOM dan koneksi ke PACS/RIS',
                'Pelatihan pengoperasian software',
                'Setup template pemeriksaan dan labeling',
                'Garansi instalasi dan support',
            ],
        ],
        [
            'title' => 'MCU Onsite',
            'description' => 'Layanan Medical Check Up (MCU) onsite di lokasi klien. Dilengkapi peralatan medis mobile dan tim medis profesional. Tersedia paket ekonomis untuk perusahaan dan RSIA.',
            'features' => [
                'MCU onsite di lokasi klien',
                'Peralatan medis mobile lengkap',
                'Tim medis profesional dan berpengalaman',
                'Hasil MCU cepat dan akurat',
                'Paket ekonomis untuk perusahaan dan RSIA',
                'Kombinasi DR + Portable + Aksesoris',
                'Kombinasi DR + Mobile X-Ray + Aksesoris + Ruangan Timbal + Perizinan BAPETEN',
            ],
        ],
        [
            'title' => 'Mobil X-Ray (Layanan Mobile)',
            'description' => 'Layanan mobile X-Ray dengan mobil unit radiologi berjalan. Dilengkapi pesawat X-Ray mobile dan DR untuk pelayanan radiologi di lokasi pasien. Cocok untuk MCU massal, bakti sosial, dan layanan kesehatan jarak jauh.',
            'features' => [
                'Mobil unit radiologi siap pakai',
                'Dilengkapi X-Ray mobile dan DR',
                'Layanan door-to-door ke lokasi klien',
                'Cocok untuk MCU massal dan bakti sosial',
                'Tenaga radiografer berpengalaman',
            ],
        ],
        [
            'title' => 'EKG (Elektrokardiografi)',
            'description' => 'Layanan pemeriksaan EKG (Elektrokardiografi) 12 lead digital untuk merekam aktivitas listrik jantung. Hasil cepat dan akurat dengan interpretasi otomatis. Tersedia untuk layanan MCU dan pemeriksaan mandiri.',
            'features' => [
                'EKG 12 lead digital',
                'Interpretasi otomatis dewasa & pediatri',
                'Hasil rekaman tersimpan dalam database',
                'Dapat diintegrasikan dengan Satu Sehat',
                'Layanan onsite dan di klinik',
            ],
        ],
        [
            'title' => 'Audiometri',
            'description' => 'Layanan pemeriksaan audiometri untuk menilai fungsi pendengaran. Dilengkapi alat audiometer kalibrasi dan ruang kedap suara portabel. Cocok untuk MCU karyawan dan pemeriksaan kesehatan kerja.',
            'features' => [
                'Pemeriksaan audiometri nada murni',
                'Alat audiometer terkalibrasi',
                'Ruang kedap suara portabel',
                'Hasil dan grafik audiogram',
                'Layanan onsite dan di klinik',
            ],
        ],
        [
            'title' => 'Spirometri',
            'description' => 'Layanan pemeriksaan spirometri untuk menilai fungsi paru-paru dan kapasitas pernapasan. Dilengkapi spirometer digital kalibrasi. Cocok untuk MCU karyawan, pemeriksaan kesehatan kerja, dan diagnosis penyakit paru.',
            'features' => [
                'Pemeriksaan spirometri digital',
                'Alat spirometer terkalibrasi',
                'Hasil lengkap FVC, FEV1, PEF, dan rasio',
                'Interpretasi dan grafik spirometri',
                'Layanan onsite dan di klinik',
            ],
        ],
        [
            'title' => 'Kertas Film Radiologi',
            'description' => 'Penyediaan kertas film untuk kebutuhan radiologi dan pencitraan medis. Tersedia berbagai merek dan ukuran untuk memenuhi kebutuhan cetak citra diagnostik.',
            'features' => [
                'Film Injek ukuran A3 dan A4',
                'Film DIHL Fuji semua ukuran',
                'Film Agfa semua ukuran',
                'Kualitas cetak tinggi dan tahan lama',
                'Kompatibel dengan berbagai printer film',
            ],
        ],
        [
            'title' => 'Paket Pintar KSO/Sewa',
            'description' => 'Solusi fleksibel KSO (Kerja Sama Operasional) dan sewa alat medis dengan berbagai sistem pembayaran. Memudahkan fasilitas kesehatan mendapatkan alat tanpa investasi besar di awal.',
            'features' => [
                'Sistem bulanan (monthly subscription)',
                'Sistem hitungan BHP (Bahan Habis Pakai)',
                'Sistem per sekali izin (per-exposure)',
                'Sistem per kali project (untuk MCU)',
                'Sistem backup (untuk alat yang sedang rusak)',
            ],
        ],
    ];

    public function definition(): array
    {
        $service = $this->faker->unique()->randomElement(static::$services);

        return $service;
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Service $service) {
            $seed = Str::random(10);
            $url = "https://picsum.photos/seed/{$seed}/800/600";
            try {
                $response = Http::timeout(15)->get($url);
                if ($response->successful()) {
                    $filename = 'services/'.Str::random(40).'.jpg';
                    Storage::disk('public')->put($filename, $response->body());
                    $service->update(['image' => $filename]);
                }
            } catch (\Exception $e) {
            }
        });
    }
}
