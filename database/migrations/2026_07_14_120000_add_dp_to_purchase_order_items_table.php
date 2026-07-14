<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->decimal('dp_persentase', 5, 2)->default(0)->after('subtotal');
            $table->decimal('dp_nominal', 15, 2)->default(0)->after('dp_persentase');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropColumn(['dp_persentase', 'dp_nominal']);
        });
    }
};
