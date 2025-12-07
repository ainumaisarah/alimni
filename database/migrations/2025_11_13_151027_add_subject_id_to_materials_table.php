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
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
{
    Schema::table('materials', function (Blueprint $table) {
        if (Schema::hasColumn('materials', 'subject_id')) {
            $table->dropColumn('subject_id');
        }
    });
}


};
