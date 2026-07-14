<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_cards', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kartu')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama_alat');
            $table->string('type_alat');
            $table->string('nama_rs_klinik');
            $table->date('tgl_instalasi');
            $table->text('catatan')->nullable();
            $table->string('verifikator', 100)->nullable();
            $table->boolean('ttd_pembeli')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_cards');
    }
};
