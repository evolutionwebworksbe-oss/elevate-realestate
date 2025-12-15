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
        Schema::create('category_display_settings', function (Blueprint $table) {
            $table->id();
            $table->string('category_type'); // 'sale' or 'rent'
            $table->string('subtype_key'); // 'woningen', 'percelen', etc.
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->unique(['category_type', 'subtype_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_display_settings');
    }
};
