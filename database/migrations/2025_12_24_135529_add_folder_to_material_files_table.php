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
        Schema::table('material_files', function (Blueprint $table) {
            $table->string('folder')->nullable()->after('file_type'); // optional folder name
        });
    }

    public function down()
    {
        Schema::table('material_files', function (Blueprint $table) {
            $table->dropColumn('folder');
        });
    }
};
