<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // hotel manager
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('neighborhood')->nullable();
            $table->string('city')->default('Benguela');
            $table->string('province')->default('Benguela');
            $table->string('country')->default('Angola');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->tinyInteger('stars')->unsigned()->default(3); // 1 a 5
            $table->decimal('price_per_night', 10, 2)->nullable(); // preço mínimo
            $table->string('cover_image')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};