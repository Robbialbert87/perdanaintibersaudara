<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    protected static $products = [
        [
            'name' => 'CT Scan 128 Slice',
            'category' => 'Radiologi',
            'description' => 'Pesawat CT Scan 128 slice dengan teknologi iterative reconstruction untuk hasil citra berkualitas tinggi dengan dosis radiasi rendah. Dilengkapi software post-processing untuk analisis 3D, angiografi, dan perfusi. Cocok untuk rumah sakit tipe A dan B.',
            'spesifikasi' => "- Tube: 128 slice\n- Gantry aperture: 70cm\n- Scan speed: 0.35s/rotasi\n- Matrix rekonstruksi: 1024x1024\n- Dosis rendah: Ya (iterative reconstruction)\n- Monitor: 24 inch LED medis",
            'satuan' => 'Unit',
            'harga_default' => 8500000000,
            'images' => ['products/aY2Pq6W66YAaTvP8zq6omdNMGhZcAsF0TEmrmyMF.jpg']
        ],
        [
            'name' => 'MRI 1.5 Tesla',
            'category' => 'Radiologi',
            'description' => 'Magnetic Resonance Imaging 1.5 Tesla dengan sistem superkonduksi dan gradien high-performance. Menghasilkan citra detail untuk pemeriksaan neurologi, muskuloskeletal, abdomen, dan kardiovaskular tanpa radiasi ionisasi.',
            'spesifikasi' => "- Kekuatan magnet: 1.5T\n- Bore size: 70cm\n- Gradien: 45 mT/m\n- RF: Multichannel\n- Software: Neuro, Cardio, MSK, Abdomen\n- Sistem pendingin: Cryogen recondensing",
            'satuan' => 'Unit',
            'harga_default' => 15000000000,
            'images' => ['products/Ijz4TlRqrfnTNd6ReBnbys2tVbWQJJySZdjArfPq.jpg']
        ],
        [
            'name' => 'X-Ray Mobile DR',
            'category' => 'Radiologi',
            'description' => 'X-Ray mobile digital radiography system dengan kemampuan battery-powered. Dilengkapi panel detektor flat-panel digital untuk hasil citra instan. Ideal untuk bed-side examination di ICU, NICU, dan ruang rawat inap.',
            'spesifikasi' => "- Generator: 32kW\n- kV range: 40-150 kV\n- mAs: 0.1-400 mAs\n- Detektor: Flat panel wireless 35x43cm\n- Baterai: Lithium-ion, 200 eksposur/charge\n- Monitor: 15 inch touchscreen",
            'satuan' => 'Unit',
            'harga_default' => 1750000000,
            'images' => ['products/7mkkow6oTkl0t7qZwGHjV0bEjI638iEffn5eEaLr.jpg']
        ],
        [
            'name' => 'USG 4D Color Doppler',
            'category' => 'Alat Diagnostik',
            'description' => 'Ultrasonography 4D dengan teknologi Color Doppler, Pulse Wave Doppler, dan Continuous Wave Doppler. Dilengkapi 3 probe (Convex, Linear, Endocavity) untuk pemeriksaan obstetri-ginekologi, abdomen, dan muskuloskeletal.',
            'spesifikasi' => "- Layar: 21.5 inch LED\n- Probe: Convex R60, Linear 50mm, Endocavity\n- Mode: 2D, M-Mode, Color Doppler, PW, CW, 4D\n- Storage: 500GB HDD\n- DICOM 3.0 compatible\n- Battery: 1 jam operasi",
            'satuan' => 'Unit',
            'harga_default' => 950000000,
            'images' => ['products/qSgBzkoRClb7aJyxBDMpPHgVOqqrZpFBajxiCc87.jpg']
        ],
        [
            'name' => 'ECG 12 Lead Digital',
            'category' => 'Kardiologi',
            'description' => 'Elektrokardiograf digital 12 lead dengan interpretasi otomatis dan layar sentuh berwarna. Dilengkapi dengan algoritma analisis dewasa dan pediatri. Dapat menyimpan hingga 1000 rekaman dan terintegrasi dengan sistem HIS via DICOM.',
            'spesifikasi' => "- Lead: 12 lead simultaneous\n- Layar: 8.4 inch color TFT\n- Interpretasi: Ya (dewasa & pediatri)\n- Storage: Internal 1000 rekaman\n- Konektivitas: USB, WiFi, LAN\n- Baterai: 4 jam pemakaian",
            'satuan' => 'Unit',
            'harga_default' => 65000000,
            'images' => ['products/edFCtKsWzyB7t6zbDKMz8a2c5QUM8gIkQf2HWugv.jpg']
        ],
        [
            'name' => 'Ventilator ICU',
            'category' => 'Life Support',
            'description' => 'Ventilator intensif care dengan mode ventilasi invasif dan non-invasif. Dilengkapi layar sentuh 15.6 inch dengan monitoring waveform dan loop. Mendukung mode VC, PC, PSV, SIMV, APRV, dan BiPAP untuk pasien dewasa hingga neonatus.',
            'spesifikasi' => "- Mode ventilasi: VC, PC, PSV, SIMV, APRV, BiPAP\n- Layar: 15.6 inch touchscreen\n- Pasien: Dewasa, Pediatri, Neonatus\n- Turbin: Internal (flow 200L/min)\n- Baterai: 2 jam internal\n- Monitoring: Waveform, Loop, CO2, SpO2",
            'satuan' => 'Unit',
            'harga_default' => 450000000,
            'images' => ['products/Ijz4TlRqrfnTNd6ReBnbys2tVbWQJJySZdjArfPq.jpg']
        ],
        [
            'name' => 'Infusion Pump',
            'category' => 'Life Support',
            'description' => 'Infusion pump volumetric dengan akurasi tinggi untuk terapi infus kontinu dan intermiten. Dilengkapi sistem keamanan anti-free flow, proteksi udara, dan oklusi. Database obat terintegrasi untuk perhitungan dosis otomatis.',
            'spesifikasi' => "- Volume: 0.1-9999 mL\n- Akurasi: ±2%\n- Kecepatan: 0.1-2000 mL/jam\n- Security: Anti-free flow, air-in-line detection\n- Database obat: 100+ obat\n- Baterai: 8 jam",
            'satuan' => 'Unit',
            'harga_default' => 35000000,
            'images' => ['products/7mkkow6oTkl0t7qZwGHjV0bEjI638iEffn5eEaLr.jpg']
        ],
        [
            'name' => 'Patient Monitor',
            'category' => 'Monitoring',
            'description' => 'Multiparameter patient monitor dengan layar 12.1 inch LED. Memonitor ECG, SPO2, NIBP, IBP, Temp, Resp, dan CO2. Dilengkapi sistem central monitoring dan alarm cerdas untuk meningkatkan keselamatan pasien.',
            'spesifikasi' => "- Layar: 12.1 inch LED\n- Parameter: ECG 5-lead, SpO2, NIBP, 2xIBP, Temp, Resp, CO2\n- Alarm: Smart alarm system\n- Central monitoring: Ya\n- Baterai: 4 jam\n- Standar: IEC 60601",
            'satuan' => 'Unit',
            'harga_default' => 125000000,
            'images' => ['products/qSgBzkoRClb7aJyxBDMpPHgVOqqrZpFBajxiCc87.jpg']
        ],
        [
            'name' => 'Defibrillator AED',
            'category' => 'Emergency',
            'description' => 'Automated External Defibrillator dengan teknologi biphasic dan panduan suara Bahasa Indonesia. Dilengkapi layar LCD untuk melihat Irama Jantung. Ringan (2.5kg) dan portable untuk penanganan henti jantung mendadak di fasilitas kesehatan.',
            'spesifikasi' => "- Mode: AED + Manual\n- Energi: 2-360J (biphasic)\n- Layar: 5.7 inch LCD\n- Panduan suara: Bahasa Indonesia\n- Berat: 2.5 kg\n- Baterai: 200 kejutan atau 4 jam monitor",
            'satuan' => 'Unit',
            'harga_default' => 85000000,
            'images' => ['products/edFCtKsWzyB7t6zbDKMz8a2c5QUM8gIkQf2HWugv.jpg']
        ],
        [
            'name' => 'Autoclave Sterilisator',
            'category' => 'Penunjang',
            'description' => 'Autoclave horizontal kapasitas besar untuk sterilisasi alat medis. Menggunakan sistem vacuum-assisted dengan siklus cepat. Dilengkapi printer untuk dokumentasi traceability dan validasi sterilisasi sesuai standar rumah sakit.',
            'spesifikasi' => "- Kapasitas: 200L\n- Chamber: Stainless steel 316L\n- Siklus: 134°C / 121°C\n- Vacuum: Fractionated pre-vacuum\n- Printer: Thermal dot matrix\n- Standar: EN 285, ISO 17665",
            'satuan' => 'Unit',
            'harga_default' => 275000000,
            'images' => ['products/aY2Pq6W66YAaTvP8zq6omdNMGhZcAsF0TEmrmyMF.jpg']
        ],
        [
            'name' => 'Operating Table Elektrik',
            'category' => 'Bedah',
            'description' => 'Meja operasi elektrik multifungsi dengan remote control wireless. Tersedia posisi Trendelenburg, Reverse Trendelenburg, Chair, dan Flex. Kapasitas beban hingga 250kg dengan kolom tengah bebas untuk akses C-Arm.',
            'spesifikasi' => "- Pergerakan: Elektrik (motorized)\n- Posisi: Trendelenburg 30°, RT 30°, Chair, Flex\n- Kapasitas: 250 kg\n- Kolom tengah: Free column untuk C-Arm\n- Remote: Wireless + panel manual\n- Material: Stainless steel anti karat",
            'satuan' => 'Unit',
            'harga_default' => 450000000,
            'images' => ['products/7mkkow6oTkl0t7qZwGHjV0bEjI638iEffn5eEaLr.jpg']
        ],
        [
            'name' => 'Lead Apron 0.5mm Pb',
            'category' => 'Safety',
            'description' => 'Apron pelindung radiasi dengan ketebalan timbal 0.5mm Pb (lead equivalent). Desain ergonomis lightweight dengan distribusi beban merata untuk kenyamanan pemakaian jangka panjang. Tersedia berbagai ukuran (S/M/L/XL).',
            'spesifikasi' => "- Lead equivalent: 0.5mm Pb\n- Berat: Mulai 4.5 kg (ukuran M)\n- Bahan: Lead rubber composite\n- Ukuran: S, M, L, XL\n- Sertifikasi: CE, FDA\n- Warna: Biru, Hijau, Hitam",
            'satuan' => 'Buah',
            'harga_default' => 2500000,
            'images' => ['products/Ijz4TlRqrfnTNd6ReBnbys2tVbWQJJySZdjArfPq.jpg']
        ],
        [
            'name' => 'ECG Paper 112mm x 100mm',
            'category' => 'Spare Part',
            'description' => 'Kertas ECG roll ukuran 112mm x 100mm (200 sheet) untuk mesin EKG standar. Kertas thermal berkualitas tinggi dengan hasil cetakan tajam dan tahan lama. Kompatibel dengan berbagai merek ECG recorder.',
            'spesifikasi' => "- Ukuran: 112mm x 100mm\n- Jumlah: 200 sheet/roll\n- Tipe: Thermal paper\n- Kompatibel: Fukuda, Nihon Kohden, Philips, Mindray\n- Pack: 12 roll/pack\n- Warna: Putih dengan grid",
            'satuan' => 'Pack',
            'harga_default' => 180000,
            'images' => ['products/qSgBzkoRClb7aJyxBDMpPHgVOqqrZpFBajxiCc87.jpg']
        ],
        [
            'name' => 'X-Ray Film 14x17 inch',
            'category' => 'Spare Part',
            'description' => 'Film radiografi ukuran 14x17 inch (35x43cm) untuk cetak citra X-Ray. Emulsi high-contrast dan low-base fog untuk kualitas citra diagnostik optimal. Kompatibel dengan prosesor otomatis konvensional.',
            'spesifikasi' => "- Ukuran: 14x17 inch (35x43cm)\n- Jenis: Blue sensitive\n- Kecepatan: 400 speed\n- Pack: 100 sheet/box\n- Storage: 2-24°C\n- Merek: Agfa, Fuji, Kodak",
            'satuan' => 'Box',
            'harga_default' => 1200000,
            'images' => ['products/edFCtKsWzyB7t6zbDKMz8a2c5QUM8gIkQf2HWugv.jpg']
        ],
        [
            'name' => 'Dental Unit Portable',
            'category' => 'Gigi',
            'description' => 'Dental unit portable lengkap dengan kompresor, suction, dan unit air. Cocok untuk klinik gigi kecil, puskesmas, dan layanan gigi mobile. Mudah dipindahkan dengan roda dan desain compact.',
            'spesifikasi' => "- Kompresor: 2L internal\n- Suction: Ya (saliva ejector)\n- Unit air: 2L botol\n- Handpiece: 2 port (high-speed + low-speed)\n- Lampu: LED operatory\n- Berat: 35 kg total",
            'satuan' => 'Unit',
            'harga_default' => 75000000,
            'images' => ['products/aY2Pq6W66YAaTvP8zq6omdNMGhZcAsF0TEmrmyMF.jpg']
        ],
    ];

    public function definition(): array
    {
        $product = $this->faker->unique()->randomElement(static::$products);

        return $product;
    }
}
