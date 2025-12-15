<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            
            // New fields only (remove gemeubileerd, huurwaarborg, beschikbaarheid)
            $table->integer('woonlagen')->nullable();
            $table->integer('woonkamer_count')->nullable();
            $table->integer('keuken_count')->nullable();
            $table->integer('toiletten_count')->nullable();
            
            // Parking
            $table->string('parkeergelegenheid_type')->nullable(); // 'open', 'closed', 'both'
            $table->integer('parkeerplaatsen_aantal')->nullable();
            
            // Airco
            $table->boolean('airco_algemeen')->default(false);
            $table->text('airco_locaties')->nullable();
            
            $table->timestamps();
            
            $table->index('property_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_details');
    }
};