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
        $table->unsignedBigInteger('quiz_id');
        $table->unsignedBigInteger('student_id');
        $table->json('answers')->nullable();
        $table->integer('score')->default(0);
        $table->timestamps();

        $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('quiz_results');
}

};
