<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\QuotationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationItemFactory extends Factory
{
    protected $model = QuotationItem::class;

    public function definition(): array
    {
        $volume = $this->faker->numberBetween(1, 10);
        $hargaSatuan = $this->faker->numberBetween(50000, 500000000);

        return [
            'nama_item' => $this->faker->randomElement([
                'CT Scan 128 Slice',
                'MRI 1.5 Tesla',
                'X-Ray Mobile DR',
                'USG 4D Color Doppler',
                'ECG 12 Lead Digital',
                'Ventilator ICU',
                'Infusion Pump',
                'Patient Monitor',
                'Defibrillator AED',
                'Autoclave Sterilisator',
                'Operating Table Elektrik',
                'Lead Apron 0.5mm Pb',
                'ECG Paper 112mm',
                'X-Ray Film 14x17',
                'Dental Unit Portable',
                'Jasa Instalasi',
                'Jasa Kalibrasi',
                'Jasa Maintenance Tahunan',
            ]),
            'deskripsi' => $this->faker->randomElement([
                'Unit baru lengkap dengan aksesoris standar',
                'Tidak termasuk instalasi dan pelatihan',
                'Harga termasuk garansi pabrik 1 tahun',
                'Barang baru, bukan bekas atau recondition',
                'Spesifikasi sesuai purchase order',
                'Termasuk pengiriman ke lokasi',
            ]),
            'volume' => $volume.' Unit',
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $volume * $hargaSatuan,
        ];
    }

    public function withProduct(Product $product): static
    {
        return $this->state(function (array $attributes) use ($product) {
            $volume = $this->faker->numberBetween(1, 5);
            $price = $product->harga_default ?: $this->faker->numberBetween(100000, 500000000);

            return [
                'product_id' => $product->id,
                'nama_item' => $product->name,
                'deskripsi' => $product->description ? substr($product->description, 0, 150) : 'Unit baru, spesifikasi sesuai standar',
                'volume' => $volume.' '.($product->satuan ?? 'Unit'),
                'harga_satuan' => $price,
                'subtotal' => $volume * $price,
            ];
        });
    }
}
