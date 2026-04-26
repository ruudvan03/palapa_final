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
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // La ruta de la imagen en el storage
            $table->string('alt')->nullable(); // Texto alternativo (ej. "Boda en la palapa")
            $table->string('category')->nullable(); // Ej. "Eventos", "Alberca"
            $table->string('cols')->nullable(); // Ej. "md:col-span-2" (Para el tamaño en el mosaico)
            $table->string('rows')->nullable(); // Ej. "md:row-span-2"
            $table->integer('order')->default(0); // Para que Josue pueda ordenar qué foto sale primero
            $table->boolean('is_active')->default(true); // Por si quiere ocultar una foto sin borrarla
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};
