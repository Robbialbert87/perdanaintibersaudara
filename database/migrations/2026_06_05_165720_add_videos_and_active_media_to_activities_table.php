<?php

use App\Models\Activity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->json('videos')->nullable()->after('images');
            $table->json('active_images')->nullable()->after('videos');
            $table->json('active_videos')->nullable()->after('active_images');
        });

        Activity::chunk(100, function ($activities) {
            foreach ($activities as $activity) {
                $activity->active_images = $activity->images ?? [];
                $activity->save();
            }
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['videos', 'active_images', 'active_videos']);
        });
    }
};
