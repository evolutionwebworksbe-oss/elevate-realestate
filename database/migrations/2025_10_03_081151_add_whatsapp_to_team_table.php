<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team', function (Blueprint $table) {
            $table->string('whatsapp', 20)->nullable()->after('phone');
        });
    }

    public function down()
    {
        Schema::table('team', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });
    }
};