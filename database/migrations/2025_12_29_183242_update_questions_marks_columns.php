<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'marks_mcq')) {
                $table->integer('marks_mcq')->default(1);
            }
            if (!Schema::hasColumn('questions', 'marks_short')) {
                $table->integer('marks_short')->default(2);
            }

            // Only drop 'marks' if it exists
            if (Schema::hasColumn('questions', 'marks')) {
                $table->dropColumn('marks');
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'marks_mcq')) {
                $table->dropColumn('marks_mcq');
            }
            if (Schema::hasColumn('questions', 'marks_short')) {
                $table->dropColumn('marks_short');
            }
            if (!Schema::hasColumn('questions', 'marks')) {
                $table->integer('marks')->default(1);
            }
        });
    }
};
