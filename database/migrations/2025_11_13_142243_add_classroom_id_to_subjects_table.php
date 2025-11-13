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
    Schema::table('subjects', function (Blueprint $table) {
        $table->unsignedBigInteger('classroom_id')->nullable()->after('id');
        $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('subjects', function (Blueprint $table) {
        $table->dropForeign(['classroom_id']);
        $table->dropColumn('classroom_id');
    });
}

};
