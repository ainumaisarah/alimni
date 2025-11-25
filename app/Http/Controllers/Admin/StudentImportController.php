<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;

class StudentImportController extends Controller
{
    public function showForm()
    {
        return view('admin.students.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv']);
        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $createdStudents = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // skip header

                [$name, $username, $password, $classNames, $teacherUsername] = $row;

                try {
                    // Find or create teacher
                    $teacher = User::firstOrCreate(
                        ['username' => $teacherUsername, 'role' => 'teacher'],
                        ['name' => $teacherUsername, 'password' => Hash::make('defaultpassword')]
                    );

                    $classList = array_map('trim', explode(',', $classNames));

                    // Use default password if blank
                    $password = $password ?: 'alimni123';

                    // Create or find student first
                    $student = User::updateOrCreate(
                        ['username' => $username, 'role' => 'student'],
                        ['name' => $name, 'password' => Hash::make($password)]
                    );

                    foreach ($classList as $className) {
                        $class = Classroom::firstOrCreate(
                            ['name' => $className],
                            ['teacher_id' => $teacher->id]
                        );

                        // Attach student to class (pivot)
                        if (!$student->classrooms->contains($class->id)) {
                            $student->classrooms()->attach($class->id);
                        }
                    }

                    $createdStudents++;

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . " ({$username}): " . $e->getMessage();
                }
            }

            $message = "$createdStudents students imported successfully.";
            if ($errors) $message .= " But some errors occurred: " . implode(' | ', $errors);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: '.$e->getMessage());
        }
    }
}
