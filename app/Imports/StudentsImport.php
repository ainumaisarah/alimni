<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;
use Excel;

class StudentsImport
{
    public $created = 0;
    public $errors = [];

    /**
     * Import students from an uploaded Excel file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function import($file)
    {
        Excel::load($file, function($reader) {

            $results = $reader->get();

            foreach ($results as $row) {
                try {
                    // -------------------------------
                    // 1. Find the teacher
                    // -------------------------------
                    $teacher = User::where('username', $row->teacher_username)
                                   ->where('role', 'teacher')
                                   ->first();

                    if (!$teacher) {
                        $this->errors[] = "Teacher '{$row->teacher_username}' not found for student '{$row->username}'";
                        continue; // skip this row
                    }

                    // -------------------------------
                    // 2. Find or create the classroom
                    // -------------------------------
                    $class = Classroom::updateOrCreate(
                        ['name' => $row->class_name],
                        ['teacher_id' => $teacher->id]
                    );
                    // -------------------------------
                    // 3. Create or update the student
                    // -------------------------------
                    $user = User::updateOrCreate(
                        ['username' => $row->username],
                        [
                            'name' => $row->name,
                            'password' => Hash::make($row->password),
                            'role' => 'student',
                            'classroom_id' => $class->id,
                        ]
                    );

                    $this->created++;

                } catch (\Exception $e) {
                    $this->errors[] = "Row with username '{$row->username}': " . $e->getMessage();
                }
            }

        });
    }
}
