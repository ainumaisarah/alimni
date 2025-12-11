<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('quiz_results', function (Blueprint $table) {
        $table->id();

        // Foreign keys
        $table->unsignedBigInteger('quiz_id');
        $table->unsignedBigInteger('student_id');

        // Store answers as JSON
        $table->json('answers')->nullable();

        // Score
        $table->integer('score')->default(0);

        $table->timestamps();

        // Foreign key constraints
        $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
    });
}


public function down()
{
    Schema::table('quizzes', function (Blueprint $table) {
        $table->dropForeign(['teacher_id']);
        $table->dropColumn('teacher_id');
    });
}

};
