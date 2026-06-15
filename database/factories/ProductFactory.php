<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    protected static $products = [
        [
            'name' => 'Portable X-Ray',
            'category' => 'Radiologi',
            'description' => 'Pesawat X-Ray portable dengan 3 jenis pilihan: tipe standar, tipe high-frequency, dan tipe digital. Cocok untuk pemeriksaan bed-side di ICU, NICU, ruang rawat inap, dan layanan MCU onsite. Ringan, mudah dipindahkan, dan dilengkapi battery backup.',
            'spesifikasi' => "- Tersedia 3 jenis: Standar, High-Frequency, Digital\n- Generator: 10-32kW\n- kV range: 40-125 kV\n- Baterai: Lithium-ion internal\n- Detektor: Compatible with CR/DR\n- Mobile: Ya (dengan roda push-handle)\n- Layar: Touchscreen LCD",
            'satuan' => 'Unit',
            'harga_default' => 850000000,
        ],
        [
            'name' => 'Mobile X-Ray Alerio',
            'category' => 'Radiologi',
            'description' => 'Mobile X-Ray Alerio dengan teknologi digital radiography terkini. Dilengkapi flat panel detector wireless untuk hasil citra instan berkualitas tinggi. Ideal untuk bed-side examination dan mobile radiografi di berbagai fasilitas kesehatan.',
            'spesifikasi' => "- Generator: 32kW high-frequency\n- kV range: 40-150 kV\n- mAs: 0.1-400 mAs\n- Detektor: Flat panel wireless 35x43cm\n- Baterai: Lithium-ion, 300+ eksposur/charge\n- Monitor: 17 inch touchscreen\n- Berat: 280 kg",
            'satuan' => 'Unit',
            'harga_default' => 1750000000,
        ],
        [
            'name' => 'DR (Digital Radiography)',
            'category' => 'Radiologi',
            'description' => 'Sistem Digital Radiography (DR) dengan flat panel detector resolusi tinggi untuk hasil citra radiografi digital langsung tanpa perlu kaset atau CR. Dilengkapi software post-processing canggih untuk diagnosis yang lebih akurat.',
            'spesifikasi' => "- Detektor: Flat panel CsI 35x43cm\n- Resolusi: 3.6 lp/mm\n- Matrix: 3072x3072 pixel\n- Range: 14x17 inch\n- Software: Post-processing, DICOM 3.0\n- Konektivitas: LAN, WiFi, USB\n- Garansi: 2 tahun",
            'satuan' => 'Unit',
            'harga_default' => 950000000,
        ],
    ];

    public function definition(): array
    {
        $product = $this->faker->unique()->randomElement(static::$products);

        return $product;
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            $images = [];
            $count = rand(1, 3);

            for ($i = 0; $i < $count; $i++) {
                $seed = Str::random(10);
                $url = "https://picsum.photos/seed/{$seed}/800/600";
                try {
                    $response = Http::timeout(15)->get($url);
                    if ($response->successful()) {
                        $filename = 'products/'.Str::random(40).'.jpg';
                        Storage::disk('public')->put($filename, $response->body());
                        $images[] = $filename;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (! empty($images)) {
                $product->update([
                    'images' => $images,
                    'active_images' => $images,
                ]);
            }
        });
    }
}
