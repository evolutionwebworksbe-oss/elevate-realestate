<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('objecten', function (Blueprint $table) {
            // Add created_at and updated_at timestamps
            $table->timestamp('created_at')->nullable()->after('last_updated');
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
        
        // Optionally, copy last_updated values to created_at and updated_at for existing records
        DB::statement('UPDATE objecten SET created_at = last_updated WHERE created_at IS NULL');
        DB::statement('UPDATE objecten SET updated_at = last_updated WHERE updated_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objecten', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
