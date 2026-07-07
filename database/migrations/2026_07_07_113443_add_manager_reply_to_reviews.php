<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('manager_reply')->nullable()->after('stay_date');
            $table->timestamp('manager_replied_at')->nullable()->after('manager_reply');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['manager_reply', 'manager_replied_at']);
        });
    }
};