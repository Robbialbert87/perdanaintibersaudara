<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->json('images')->nullable()->after('image');
            $table->json('videos')->nullable()->after('images');
            $table->json('active_images')->nullable()->after('videos');
            $table->json('active_videos')->nullable()->after('active_images');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['images', 'videos', 'active_images', 'active_videos']);
        });
    }
};
