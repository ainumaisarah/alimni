<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // 1️⃣ Add subject_id column
            $table->foreignId('subject_id')->nullable()->after('teacher_id')->constrained()->cascadeOnDelete();

            // 2️⃣ Optional: if you want to migrate old data from 'subject' column,
            // you'll need to map strings to the actual subject IDs manually.

            // 3️⃣ Drop the old 'subject' column
            $table->dropColumn('subject');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // 1️⃣ Restore 'subject' column
            $table->string('subject')->after('teacher_id');

            // 2️⃣ Drop the foreign key
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};
