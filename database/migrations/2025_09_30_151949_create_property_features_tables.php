<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Voorzieningen (Facilities)
        Schema::create('voorzieningen', function (Blueprint $table) {
            $table->id();
            $table->string('naam'); // electricity, water, wifi, gas, etc.
        });
        
        // Pivot table
        Schema::create('property_voorzieningen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('voorziening_id');
            
        });
        
        // Beveiliging (Security)
        Schema::create('beveiliging_types', function (Blueprint $table) {
            $table->id();
            $table->string('naam'); // alarm, camera, security_guard, etc.
        });
        
        Schema::create('property_beveiliging', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('beveiliging_type_id');
            
            $table->foreign('property_id')->references('id')->on('objecten')->onDelete('cascade');
            $table->foreign('beveiliging_type_id')->references('id')->on('beveiliging_types')->onDelete('cascade');
        });
        
        // Extra Ruimtes (Extra Spaces)
        Schema::create('extra_ruimte_types', function (Blueprint $table) {
            $table->id();
            $table->string('naam'); // terras, balkon, berging, etc.
        });
        
        Schema::create('property_extra_ruimtes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('extra_ruimte_type_id');
            
            $table->foreign('property_id')->references('id')->on('objecten')->onDelete('cascade');
            $table->foreign('extra_ruimte_type_id')->references('id')->on('extra_ruimte_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_extra_ruimtes');
        Schema::dropIfExists('extra_ruimte_types');
        Schema::dropIfExists('property_beveiliging');
        Schema::dropIfExists('beveiliging_types');
        Schema::dropIfExists('property_voorzieningen');
        Schema::dropIfExists('voorzieningen');
    }
};