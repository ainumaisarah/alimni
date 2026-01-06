<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TeachersImport
{
    public $createdTeachers = 0;
    public $createdClassrooms = 0;
    public $errors = [];

    public function import($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // skip header

            try {
                // Only require name and username
                if (empty($row[0]) || empty($row[1])) {
                    $this->errors[] = "Row " . ($index + 1) . " is missing required fields.";
                    continue;
                }

                [$name, $username, $password, $className] = $row;

                $password = $password ?: 'password123';

                // Create or update teacher
                $teacher = User::updateOrCreate(
                    ['username' => $username],
                    ['name' => $name, 'password' => Hash::make($password), 'role' => 'teacher']
                );
                if ($teacher->wasRecentlyCreated) $this->createdTeachers++;

                // Only create classroom if a name is provided
                if (!empty($className)) {
                    $class = Classroom::updateOrCreate(
                        ['name' => $className],
                        ['teacher_id' => $teacher->id]
                    );
                    if ($class->wasRecentlyCreated) $this->createdClassrooms++;
                }

            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 1) . " (Teacher: '{$username}', Class: '{$className}'): "
                    . $e->getMessage();
            }
        }
    }

    public function summary()
    {
        $message = "{$this->createdTeachers} new teacher(s) imported successfully";
        $message .= ", {$this->createdClassrooms} new classroom(s) created.";

        if (!empty($this->errors)) {
            $message .= " Some errors occurred: " . implode(' | ', $this->errors);
        }

        return $message;
    }
}
