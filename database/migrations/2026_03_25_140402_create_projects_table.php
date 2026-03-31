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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title_nl');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('excerpt_nl')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->longText('description_nl')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('featured_image')->nullable();
            $table->foreignId('project_type_id')->nullable()->constrained('project_types')->nullOnDelete();
            $table->enum('status', ['ongoing', 'completed', 'coming_soon', 'planning'])->default('ongoing');
            $table->string('location')->nullable();
            $table->unsignedInteger('total_units')->nullable();
            $table->decimal('total_area', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
