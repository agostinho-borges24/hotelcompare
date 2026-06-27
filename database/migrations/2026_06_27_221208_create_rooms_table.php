<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name'); // ex: "Suite Deluxe", "Standard"
            $table->enum('type', ['single', 'double', 'suite', 'family', 'presidential'])->default('double');
            $table->text('description')->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->unsignedTinyInteger('max_guests')->default(2);
            $table->unsignedTinyInteger('beds')->default(1);
            $table->boolean('has_ac')->default(false);
            $table->boolean('has_tv')->default(false);
            $table->boolean('has_wifi')->default(false);
            $table->boolean('has_private_bathroom')->default(true);
            $table->boolean('is_available')->default(true); // actualização em tempo real
            $table->unsignedInteger('total_units')->default(1);
            $table->unsignedInteger('available_units')->default(1);
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};