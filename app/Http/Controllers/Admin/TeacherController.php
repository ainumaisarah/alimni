<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function showForm()
    {
        return view('admin.teachers.import'); // create this blade like student import
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv']);
        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $createdTeachers = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // skip header

                [$name, $username, $password] = $row;

                try {
                    // Use default password if blank
                    $password = $password ?: 'alimni123';

                    // Create or update teacher
                    User::updateOrCreate(
                        ['username' => $username, 'role' => 'teacher'],
                        ['name' => $name, 'password' => Hash::make($password)]
                    );

                    $createdTeachers++;

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . " ({$username}): " . $e->getMessage();
                }
            }

            $message = "$createdTeachers teachers imported successfully.";
            if ($errors) $message .= " Some errors occurred: " . implode(' | ', $errors);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: '.$e->getMessage());
        }
    }
}
