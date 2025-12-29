<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_allow_late_submission_to_assignments_table.php
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->boolean('allow_late_submission')->default(false);
        });
    }

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('allow_late_submission');
        });
    }

};
