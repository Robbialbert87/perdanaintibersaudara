<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->text('perihal');
            $table->string('perihal_surat', 255)->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['draft', 'dikirim', 'dikonfirmasi', 'batal'])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('kata_pengantar')->nullable();
            $table->text('kata_penutup')->nullable();
            $table->boolean('tampilkan_gambar')->default(false);
            $table->json('selected_images')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
