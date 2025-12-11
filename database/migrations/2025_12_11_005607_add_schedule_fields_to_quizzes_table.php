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
        if (!Schema::hasColumn('quizzes', 'show_answers')) {
            $table->boolean('show_answers')->default(false)->after('description');
        }
        if (!Schema::hasColumn('quizzes', 'duration')) {
            $table->integer('duration')->nullable()->after('show_answers');
        }
        if (!Schema::hasColumn('quizzes', 'open_at')) {
            $table->timestamp('open_at')->nullable()->after('duration');
        }
        if (!Schema::hasColumn('quizzes', 'due_at')) {
            $table->timestamp('due_at')->nullable()->after('open_at');
        }
});

    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['show_answers','duration','open_at','due_at']);
        });
    }
};
