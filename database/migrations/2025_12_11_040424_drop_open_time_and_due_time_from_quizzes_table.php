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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['open_time', 'due_time']);
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->datetime('open_time')->nullable();
            $table->datetime('due_time')->nullable();
        });
    }
};
