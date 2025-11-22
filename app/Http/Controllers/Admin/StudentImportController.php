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
        return view('admin.students.import'); // Blade for upload form
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $createdStudents = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header row

                [$name, $username, $password, $className, $teacherUsername] = $row;

                try {
                    // Find or create teacher
                    $teacher = User::firstOrCreate(
                        ['username' => $teacherUsername, 'role' => 'teacher'],
                        [
                            'name' => $teacherUsername,
                            'password' => Hash::make('defaultpassword'), // You can change default
                        ]
                    );

                    // Find or create class and assign teacher
                    $class = Classroom::updateOrCreate(
                        ['name' => $className],
                        ['teacher_id' => $teacher->id]
                    );

                    // Create or update student
                    User::updateOrCreate(
                        ['username' => $username],
                        [
                            'name' => $name,
                            'password' => Hash::make($password),
                            'role' => 'student',
                            'classroom_id' => $class->id,
                        ]
                    );

                    $createdStudents++;

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . " ({$username}): " . $e->getMessage();
                }
            }

            $message = "$createdStudents students imported successfully.";
            if (count($errors) > 0) {
                $message .= " But some errors occurred: " . implode(' | ', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: '.$e->getMessage());
        }
    }
}
