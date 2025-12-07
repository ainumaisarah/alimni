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
        Schema::create('posts', function (Blueprint $table) {
        $table->id(); // unsignedBigInteger primary key
        $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('title')->nullable()->change();
        $table->text('content');
        $table->timestamps();
    });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');

        Schema::table('posts', function (Blueprint $table) {
        $table->string('title')->nullable(false)->change();
    });
    }
};
