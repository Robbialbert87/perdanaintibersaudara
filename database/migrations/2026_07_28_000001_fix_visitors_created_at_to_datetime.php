<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE visitors MODIFY COLUMN created_at DATETIME NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE visitors MODIFY COLUMN created_at TIMESTAMP NULL');
    }
};