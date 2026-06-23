<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('quotations')
            ->whereNotNull('catatan')
            ->where('catatan', '!=', '')
            ->update(['catatan' => DB::raw("TRIM(catatan)")]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak bisa mengembalikan data yang sudah di-trim
    }
};
