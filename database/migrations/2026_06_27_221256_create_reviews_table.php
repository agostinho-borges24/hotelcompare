<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned(); // 1 a 5
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            // Sub-ratings
            $table->tinyInteger('rating_cleanliness')->unsigned()->nullable();
            $table->tinyInteger('rating_service')->unsigned()->nullable();
            $table->tinyInteger('rating_location')->unsigned()->nullable();
            $table->tinyInteger('rating_value')->unsigned()->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('stay_date')->nullable();
            $table->timestamps();

            // Um utilizador só pode avaliar um hotel uma vez
            $table->unique(['hotel_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};