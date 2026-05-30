<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('perihal');
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['draft', 'dikirim', 'deal', 'batal'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
