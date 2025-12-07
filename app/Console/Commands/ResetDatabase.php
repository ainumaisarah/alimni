<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ResetDatabase extends Command
{
    protected $signature = 'db:reset';
    protected $description = 'Reset database safely with foreign key checks disabled';

    public function handle()
    {
        $this->info('Disabling foreign key checks...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->info('Dropping all tables...');
        // Drop all tables manually if needed
        $tables = ['classroom_student', 'materials', 'quizzes', 'schedules', 'classrooms', 'posts', 'comments'];
        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS {$table};");
            $this->info("Dropped table: {$table}");
        }

        $this->info('Re-enabling foreign key checks...');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Running migrations...');
        Artisan::call('migrate');

        $this->info('Database reset completed!');
    }
}
