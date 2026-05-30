<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nama_instansi',
        'alamat',
        'kota',
        'telepon',
        'email',
        'contact_person'
    ];
}
