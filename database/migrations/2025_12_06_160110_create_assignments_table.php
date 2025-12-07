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
        Schema::create('assignments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('classroom_id');
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('file')->nullable(); // store file path
    $table->timestamps();

    $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
