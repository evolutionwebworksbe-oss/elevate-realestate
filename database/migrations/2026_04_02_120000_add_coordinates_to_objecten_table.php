<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('objecten', function (Blueprint $table) {
            if (! Schema::hasColumn('objecten', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('address');
            }

            if (! Schema::hasColumn('objecten', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('objecten', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('objecten', 'longitude')) {
                $columnsToDrop[] = 'longitude';
            }

            if (Schema::hasColumn('objecten', 'latitude')) {
                $columnsToDrop[] = 'latitude';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
