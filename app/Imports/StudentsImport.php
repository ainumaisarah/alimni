<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentsImport
{
    public $created = 0;
    public $errors = [];

    /**
     * Import students from Excel
     */
    public function import($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // skip header row

            [$name, $username, $password, $className] = $row;

            try {
                $password = $password ?: 'alimni123';

                // 1. Create or update student
                $student = User::updateOrCreate(
                    ['username' => $username, 'role' => 'student'],
                    ['name' => $name, 'password' => Hash::make($password)]
                );

                if ($student->wasRecentlyCreated) {
                    $this->created++;
                }

                // 2. Find or create classroom
                $class = Classroom::firstOrCreate(['name' => $className]);

                // 3. Attach student to class (avoid duplicates)
                if (!$student->classrooms()->where('classroom_id', $class->id)->exists()) {
                    $student->classrooms()->attach($class->id);
                }

            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 1) . " ({$username}): " . $e->getMessage();
            }
        }
    }

    /**
     * Return a summary message after import
     */
    public function summary()
    {
        $message = "{$this->created} new student(s) imported successfully.";

        if (!empty($this->errors)) {
            $message .= " Some errors occurred: " . implode(' | ', $this->errors);
        }

        return $message;
    }
}
