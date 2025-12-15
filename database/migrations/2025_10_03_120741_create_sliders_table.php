<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('title_en', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image');
            $table->integer('order')->default(0);
            $table->boolean('active')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sliders');
    }
};