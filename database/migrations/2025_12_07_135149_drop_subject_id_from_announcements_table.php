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
    Schema::table('announcements', function (Blueprint $table) {
        $table->dropForeign(['subject_id']); // drop foreign key
        $table->dropColumn('subject_id');   // drop column
    });
}

public function down(): void
{
    Schema::table('announcements', function (Blueprint $table) {
        $table->unsignedBigInteger('subject_id')->nullable();
        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
    });
}

};
