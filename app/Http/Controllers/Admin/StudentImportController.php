<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;

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

        $importer = new StudentsImport();
        $importer->import($file);

        return redirect()->back()->with('success', $importer->summary());
    }
}
