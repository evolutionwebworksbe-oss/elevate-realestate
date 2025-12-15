<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_member_titles', function (Blueprint $table) {
            $table->id();
            
            // Use integer (signed) to match the team table's int id column
            $table->integer('team_member_id');
            // Use integer (signed) to match the team_title_type table's int id column
            $table->integer('team_title_type_id');
            $table->timestamps();

            $table->foreign('team_member_id')->references('id')->on('team')->onDelete('cascade');
            $table->foreign('team_title_type_id')->references('id')->on('team_title_type')->onDelete('cascade');
            
            $table->unique(['team_member_id', 'team_title_type_id'], 'team_member_title_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_member_titles');
    }
};
