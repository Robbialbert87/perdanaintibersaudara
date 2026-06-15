<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    protected static $activities = [
        [
            'title' => 'Instalasi CT Scan 128 Slice di RSUD Raden Mattaher Jambi',
            'content' => 'Tim (PIB) Perdana Inti Bersaudara berhasil menyelesaikan instalasi dan komisioning pesawat CT Scan 128 slice di RSUD Raden Mattaher Provinsi Jambi. Proyek ini meliputi persiapan ruangan (shielding radiasi), instalasi perangkat, uji fungsi, dan pelatihan operator. Dengan hadirnya CT Scan ini, masyarakat Jambi kini dapat mengakses layanan pemeriksaan citra diagnostik berkualitas tinggi tanpa harus dirujuk ke luar kota.',
            'date' => '2025-11-15',
        ],
        [
            'title' => 'Kontrak Maintenance Tahunan MRI RS Abdul Manap Kota Jambi',
            'content' => '(PIB) Perdana Inti Bersaudara dipercaya kembali untuk menangani maintenance tahunan MRI 1.5 Tesla di RS Abdul Manap Kota Jambi. Kontrak ini mencakup pemeriksaan berkala, kalibrasi rutin, penggantian suku cadang, dan respon service 1x24 jam. Kami berkomitmen menjaga performa MRI agar tetap optimal dalam melayani pasien.',
            'date' => '2026-01-10',
        ],
        [
            'title' => 'Pengadaan Lengkap Alat Medis Klinik Pratama Rawasari',
            'content' => 'Proyek pengadaan alat medis lengkap untuk Klinik Pratama Rawasari telah selesai dilaksanakan. Pengadaan meliputi patient monitor, ECG, USG, defibrillator, autoclave, dan berbagai alat penunjang lainnya. Kami juga melakukan instalasi, uji fungsi, dan pelatihan bagi tenaga kesehatan klinik.',
            'date' => '2025-09-20',
        ],
        [
            'title' => 'Kalibrasi Alat Medis RS Siloam Hospitals Jambi',
            'content' => 'Melaksanakan kalibrasi berkala untuk 35 unit alat medis di RS Siloam Hospitals Jambi, meliputi ECG, defibrillator, infusion pump, ventilator, dan patient monitor. Seluruh alat dinyatakan la pakai dan memenuhi standar akurasi yang ditetapkan. Sertifikat kalibrasi resmi telah diterbitkan untuk setiap alat.',
            'date' => '2026-03-05',
        ],
        [
            'title' => 'Instalasi Sistem Radiologi Digital RS Bunda Medical Center',
            'content' => 'Menyelesaikan instalasi sistem radiologi digital (CR/DR) di RS Bunda Medical Center Jambi. Proyek ini mencakup pengadaan X-Ray mobile DR, panel detektor digital, dan workstation radiologi dengan integrasi PACS/RIS. Hasil citra digital dapat langsung diakses oleh dokter melalui sistem informasi rumah sakit.',
            'date' => '2025-07-12',
        ],
        [
            'title' => 'Pengadaan USG 4D untuk Klinik Sehat Keluarga',
            'content' => 'Melakukan pengadaan dan instalasi USG 4D Color Doppler untuk Klinik Sehat Keluarga di Kota Jambi. Unit USG terbaru ini dilengkapi tiga probe (convex, linear, endocavity) dan fitur 4D rendering untuk pemeriksaan obstetri. Pelatihan operator telah dilakukan selama 3 hari.',
            'date' => '2026-04-18',
        ],
        [
            'title' => 'Perbaikan Ventilator ICU RSUD H. Hanafie Muara Bungo',
            'content' => 'Menangani perbaikan darurat 3 unit ventilator ICU di RSUD H. Hanafie, Muara Bungo. Diagnosa masalah meliputi kerusakan modul oksigen sensor dan kebocoran sistem pneumatik. Seluruh perbaikan berhasil diselesaikan dalam waktu 2 hari, mengembalikan fungsi ventilator secara penuh untuk mendukung perawatan pasien kritis.',
            'date' => '2025-05-22',
        ],
    ];

    public function definition(): array
    {
        $activity = $this->faker->unique()->randomElement(static::$activities);

        return $activity;
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Activity $activity) {
            $images = [];
            $count = rand(1, 2);

            for ($i = 0; $i < $count; $i++) {
                $seed = Str::random(10);
                $url = "https://picsum.photos/seed/{$seed}/800/600";
                try {
                    $response = Http::timeout(15)->get($url);
                    if ($response->successful()) {
                        $filename = 'activities/'.Str::random(40).'.jpg';
                        Storage::disk('public')->put($filename, $response->body());
                        $images[] = $filename;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (! empty($images)) {
                $activity->update([
                    'images' => $images,
                    'active_images' => $images,
                ]);
            }
        });
    }
}
