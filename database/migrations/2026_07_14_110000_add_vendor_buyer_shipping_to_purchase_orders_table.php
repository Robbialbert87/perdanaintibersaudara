<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('vendor', 255)->nullable()->after('tanggal');
            $table->string('vendor_address')->nullable()->after('vendor');
            $table->string('vendor_cp', 255)->nullable()->after('vendor_address');
            $table->string('vendor_phone', 50)->nullable()->after('vendor_cp');
            $table->string('buyer_name', 255)->nullable()->after('vendor_phone');
            $table->string('buyer_address')->nullable()->after('buyer_name');
            $table->string('buyer_cp', 255)->nullable()->after('buyer_address');
            $table->string('buyer_phone', 50)->nullable()->after('buyer_cp');
            $table->string('shipping_name', 255)->nullable()->after('buyer_phone');
            $table->string('shipping_address')->nullable()->after('shipping_name');
            $table->string('shipping_cp', 255)->nullable()->after('shipping_address');
            $table->string('shipping_phone', 50)->nullable()->after('shipping_cp');
            $table->decimal('discount', 15, 2)->default(0)->after('total');
            $table->decimal('ppn', 15, 2)->default(0)->after('discount');
            $table->decimal('grand_total', 15, 2)->default(0)->after('ppn');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'vendor', 'vendor_address', 'vendor_cp', 'vendor_phone',
                'buyer_name', 'buyer_address', 'buyer_cp', 'buyer_phone',
                'shipping_name', 'shipping_address', 'shipping_cp', 'shipping_phone',
                'discount', 'ppn', 'grand_total',
            ]);
        });
    }
};
