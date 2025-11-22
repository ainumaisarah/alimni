<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove subject_id from announcements first
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'subject_id')) {
                $table->dropForeign(['subject_id']); // drop foreign key
                $table->dropColumn('subject_id');    // drop the column
            }
        });

        // Drop the subjects table
        Schema::dropIfExists('subjects');
    }

    public function down(): void
    {
        // Recreate subjects table if needed
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Add subject_id back to announcements
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
        });
    }
};
