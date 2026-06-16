<?php

namespace Database\Factories;

use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    protected static $perihalOptions = [
        'Pengadaan Alat Kesehatan',
        'Pengadaan Radiologi Digital',
        'Pengadaan USG 4D Color Doppler',
        'Pengadaan Ventilator ICU',
        'Pengadaan Patient Monitor',
        'Pengadaan ECG 12 Lead',
        'Pengadaan Infusion Pump',
        'Pengadaan Defibrillator',
        'Jasa Maintenance Alat Medis',
        'Jasa Kalibrasi Alat Medis',
        'Jasa Instalasi CT Scan',
        'Pengadaan Lead Apron & Safety',
        'Spare Part & Aksesoris Alat Medis',
        'Jasa Perbaikan Ventilator',
        'Pengadaan Meja Operasi',
    ];

    protected static $catatanOptions = [
        'Harga belum termasuk PPN 11%.',
        'Harga termasuk ongkos kirim area Jambi.',
        'Pembayaran DP 50% di awal, pelunasan setelah instalasi.',
        'Garansi 1 tahun untuk semua perangkat.',
        'Harga sudah termasuk instalasi dan pelatihan operator.',
        'Ketentuan lebih lanjut sesuai syarat dan ketentuan yang berlaku.',
        'Penawaran berlaku selama 30 hari sejak tanggal surat.',
    ];

    public function definition(): array
    {
        $month = $this->faker->numberBetween(1, 12);
        $year = $this->faker->numberBetween(2025, 2026);
        $day = $this->faker->numberBetween(1, 28);
        $tanggal = Carbon::create($year, $month, $day);
        $roman = self::romanize($month);
        $seq = $this->faker->unique()->numberBetween(1, 50);
        $romanYear = $year;

        return [
            'nomor_surat' => sprintf('%03d/SP/PIB-JMB/%s/%d', $seq, $roman, $romanYear),
            'tanggal' => $tanggal,
            'perihal' => [$this->faker->randomElement(static::$perihalOptions)],
            'total' => 0,
            'status' => $this->faker->randomElement(['draft', 'dikirim', 'deal', 'batal']),
            'catatan' => $this->faker->randomElement(static::$catatanOptions),
        ];
    }

    protected static function romanize($num)
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $result = '';
        foreach ($map as $roman => $value) {
            while ($num >= $value) {
                $result .= $roman;
                $num -= $value;
            }
        }

        return $result;
    }
}
