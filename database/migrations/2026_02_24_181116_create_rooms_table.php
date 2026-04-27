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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0); 
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->string('image_path')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->integer('capacity');
            $table->string('capacity_label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};