<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('objecten', function (Blueprint $table) {
            $table->string('naam_en', 50)->nullable()->after('naam');
            $table->text('omschrijving_en')->nullable()->after('omschrijving');
        });
    }

    public function down()
    {
        Schema::table('objecten', function (Blueprint $table) {
            $table->dropColumn(['naam_en', 'omschrijving_en']);
        });
    }
};