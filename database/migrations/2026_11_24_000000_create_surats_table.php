<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable()->unique();
            $table->string('jenis')->default('Permintaan');
            $table->date('tanggal');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tujuan_nama');
            $table->text('tujuan_alamat')->nullable();
            $table->string('perihal');
            $table->text('isi');
            $table->json('lampiran')->nullable();
            $table->string('nama_penandatangan')->default('Erwin Darmawan');
            $table->string('jabatan_penandatangan')->default('Direktur');
            $table->string('status')->default('draft');
            $table->uuid('verify_token')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
