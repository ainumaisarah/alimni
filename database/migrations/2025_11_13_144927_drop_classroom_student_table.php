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
    Schema::dropIfExists('classroom_student');
}

public function down()
{
    Schema::create('classroom_student', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('classroom_id');
        $table->unsignedBigInteger('student_id');
        $table->timestamps();
    });
}

};
