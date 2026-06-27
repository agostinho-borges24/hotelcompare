<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Comodidades base (Wi-Fi, Piscina, Parque, etc.)
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable(); // ex: "wifi", "pool"
            $table->string('category')->nullable(); // ex: "lazer", "serviços"
            $table->timestamps();
        });

        // Tabela pivot hotel <-> amenity
        Schema::create('hotel_amenity', function (Blueprint $table) {
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->primary(['hotel_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_amenity');
        Schema::dropIfExists('amenities');
    }
};