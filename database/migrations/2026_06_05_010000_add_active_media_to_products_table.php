<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('active_images')->nullable()->after('images');
            $table->json('active_videos')->nullable()->after('videos');
        });

        Product::whereNotNull('images')->chunk(100, function ($products) {
            foreach ($products as $product) {
                $product->active_images = $product->images;
                $product->active_videos = $product->videos;
                $product->save();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['active_images', 'active_videos']);
        });
    }
};
