<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('subject_id')->after('title')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
{
    Schema::table('quizzes', function (Blueprint $table) {
        // Drop foreign key only if the column exists
        if (Schema::hasColumn('quizzes', 'subject_id')) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = $sm->listTableForeignKeys('quizzes');
            foreach ($foreignKeys as $fk) {
                if ($fk->getLocalColumns()[0] === 'subject_id') {
                    $table->dropForeign($fk->getName());
                }
            }

            $table->dropColumn('subject_id');
        }
    });
}

};
