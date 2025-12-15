<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('objectFotos', function (Blueprint $table) {
            $table->integer('display_order')->default(0)->after('url');
        });
        
        // Set initial order based on existing IDs
        DB::statement('UPDATE objectFotos SET display_order = id');
    }

    public function down(): void
    {
        Schema::table('objectFotos', function (Blueprint $table) {
            $table->dropColumn('display_order');
        });
    }
};
