<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    // List all teachers
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    // Delete teacher
    public function destroy($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
                         ->with('success', 'Teacher deleted successfully.');
    }

    // Show import form
    public function showForm()
    {
        return view('admin.teachers.import'); // create this blade like student import
    }

    // Import teachers from Excel
    // In TeacherController
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');

        try {
            // Use your existing TeachersImport class
            $importer = new \App\Imports\TeachersImport();
            $importer->import($file);

            // Get a proper summary message with counts and errors
            $message = $importer->summary();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
