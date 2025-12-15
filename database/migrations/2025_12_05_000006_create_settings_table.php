<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, file
            $table->string('group')->default('general'); // general, images, watermark, seo
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('settings')->insert([
            // Image Optimization Settings
            [
                'key' => 'image_optimization_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'image_max_width',
                'value' => '1920',
                'type' => 'number',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'image_max_height',
                'value' => '1080',
                'type' => 'number',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'image_quality',
                'value' => '85',
                'type' => 'number',
                'group' => 'images',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Watermark Settings
            [
                'key' => 'watermark_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'watermark',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'watermark_path',
                'value' => 'images/watermark.png',
                'type' => 'file',
                'group' => 'watermark',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'watermark_size',
                'value' => '30',
                'type' => 'number',
                'group' => 'watermark',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'watermark_opacity',
                'value' => '50',
                'type' => 'number',
                'group' => 'watermark',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'watermark_position',
                'value' => 'center',
                'type' => 'text',
                'group' => 'watermark',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
