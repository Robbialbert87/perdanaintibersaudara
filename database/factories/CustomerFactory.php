<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    protected static $customers = [
        [
            'nama_instansi' => 'RSUD Raden Mattaher Provinsi Jambi',
            'alamat' => 'Jl. Prof. DR. Moh. Yamin No.30, Legok, Kec. Telanaipura',
            'kota' => 'Jambi',
            'telepon' => '0741-60277',
            'email' => 'rsud_mattaher@jambiprov.go.id',
            'contact_person' => 'dr. H. Rahmad Hidayat, Sp.Rad',
        ],
        [
            'nama_instansi' => 'RS Abdul Manap Kota Jambi',
            'alamat' => 'Jl. Kol. M. Bauta No.86, Thehok, Kec. Jambi Selatan',
            'kota' => 'Jambi',
            'telepon' => '0741-23456',
            'email' => 'info@rsabdulmanap.jambi.go.id',
            'contact_person' => 'Drs. H. Syafrial, M.Kes',
        ],
        [
            'nama_instansi' => 'RS Siloam Hospitals Jambi',
            'alamat' => 'Jl. Soekarno Hatta No.156, Solok Sipin, Danau Sipin',
            'kota' => 'Jambi',
            'telepon' => '0741-581888',
            'email' => 'jambi@siloamhospitals.com',
            'contact_person' => 'dr. Andika Pratama, Sp.PK',
        ],
        [
            'nama_instansi' => 'RS Bunda Medical Center Jambi',
            'alamat' => 'Jl. HOS Cokroaminoto No.1, Sungai Putri, Danau Teluk',
            'kota' => 'Jambi',
            'telepon' => '0741-581999',
            'email' => 'bmc.jambi@bundamedik.co.id',
            'contact_person' => 'Hj. Dewi Sartika, S.Kep',
        ],
        [
            'nama_instansi' => 'Klinik Pratama Rawasari',
            'alamat' => 'Jl. Rawasari No.45, Paal Lima, Kec. Kota Baru',
            'kota' => 'Jambi',
            'telepon' => '0741-34567',
            'email' => 'klinikrawasari@gmail.com',
            'contact_person' => 'dr. Fitriani Agustina',
        ],
        [
            'nama_instansi' => 'Klinik Sehat Keluarga',
            'alamat' => 'Jl. Diponegoro No.78, Jelutung',
            'kota' => 'Jambi',
            'telepon' => '0741-67890',
            'email' => 'sehatkeluarga@gmail.com',
            'contact_person' => 'H. Ahmad Fauzi, S.Kep',
        ],
        [
            'nama_instansi' => 'Puskesmas Simpang IV Sipin',
            'alamat' => 'Jl. Simpang IV Sipin No.12, Sipin, Kota Baru',
            'kota' => 'Jambi',
            'telepon' => '0741-45678',
            'email' => 'pkm_simpang4@jambi.go.id',
            'contact_person' => 'dr. Nurlela Hakim',
        ],
        [
            'nama_instansi' => 'RSUD H. Hanafie Muara Bungo',
            'alamat' => 'Jl. Lintas Sumatra KM. 5, Muara Bungo',
            'kota' => 'Muara Bungo',
            'telepon' => '0747-32345',
            'email' => 'rsud_hanafie@bungokab.go.id',
            'contact_person' => 'drg. H. Zulkifli, MM',
        ],
        [
            'nama_instansi' => 'RS Jiwa Daerah Jambi',
            'alamat' => 'Jl. H. Agus Salim No.99, Payo Lebar, Jelutung',
            'kota' => 'Jambi',
            'telepon' => '0741-56789',
            'email' => 'rsjd.jambi@gmail.com',
            'contact_person' => 'dr. Henny Rachmawati, Sp.KJ',
        ],
        [
            'nama_instansi' => 'Klinik Utama Medika Lestari',
            'alamat' => 'Jl. Pattimura No.55, Beringin, Kec. Alam Barajo',
            'kota' => 'Jambi',
            'telepon' => '0741-78901',
            'email' => 'medikalestari96@gmail.com',
            'contact_person' => 'dr. Rizky Ananda',
        ],
        [
            'nama_instansi' => 'RSUD Sultan Thaha Saifuddin Muara Tebo',
            'alamat' => 'Jl. Lintas Jambi - Muara Bungo Km. 85, Muara Tebo',
            'kota' => 'Muara Tebo',
            'telepon' => '0744-21888',
            'email' => 'rsud_sultan@tebokab.go.id',
            'contact_person' => 'dr. H. Ali Umar, Sp.B',
        ],
        [
            'nama_instansi' => 'Labkesda Provinsi Jambi',
            'alamat' => 'Jl. Prof. DR. Sri Soedewi No.1, Telanaipura',
            'kota' => 'Jambi',
            'telepon' => '0741-61234',
            'email' => 'labkesda@jambiprov.go.id',
            'contact_person' => 'Dra. Hj. Siti Rahmah, Apt, M.Si',
        ],
    ];

    public function definition(): array
    {
        $customer = $this->faker->unique()->randomElement(static::$customers);

        return $customer;
    }
}
