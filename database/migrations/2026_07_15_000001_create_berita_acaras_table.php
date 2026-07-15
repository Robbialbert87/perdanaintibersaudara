<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->text('lokasi');
            $table->string('pihak_penyerah_nama')->default('CV. Perdana Inti Bersaudara');
            $table->text('pihak_penyerah_alamat')->nullable();
            $table->string('pihak_penerima_nama');
            $table->text('pihak_penerima_alamat')->nullable();
            $table->text('closing_text')->nullable();
            $table->string('status')->default('draft');
            $table->uuid('verify_token')->nullable();
            $table->timestamps();
        });

        Schema::create('berita_acara_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_id')->constrained()->cascadeOnDelete();
            $table->string('nama_produk');
            $table->integer('quantity')->default(1);
            $table->boolean('berfungsi')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara_items');
        Schema::dropIfExists('berita_acaras');
    }
};
