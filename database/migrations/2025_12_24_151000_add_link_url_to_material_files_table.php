<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::table('material_files', function (Blueprint $table) {
            $table->string('link_url')->nullable()->after('file_type');
        });
    }

    public function down()
    {
        Schema::table('material_files', function (Blueprint $table) {
            $table->dropColumn('link_url');
        });
    }
};
