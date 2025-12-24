<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialFile;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class MaterialController extends Controller
{
    /* ===========================
       TEACHER: LIST MATERIALS
    ============================ */
    public function teacherIndex()
    {
        $materials = Material::where('teacher_id', Auth::id())
            ->with(['classroom', 'files'])
            ->latest()
            ->get();

        return view('teacher.materials.index', compact('materials'));
    }

    /* ===========================
       CLASS MATERIALS (STUDENT & TEACHER)
    ============================ */
    public function classMaterials($classId)
    {
        $materials = Material::where('classroom_id', $classId)
            ->with('files')
            ->latest()
            ->get();

        return view('classes.materials', compact('materials'));
    }

    /* ===========================
       SHOW CREATE FORM
    ============================ */
    public function create(Request $request)
    {
        $classroomId = $request->query('classroom_id');
        $classrooms = Classroom::all();

        return view('teacher.materials.create', compact('classrooms', 'classroomId'));
    }

    /* ===========================
       STORE MATERIAL (MULTI FILE)
    ============================ */
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'files.*' => 'nullable|file|max:10240', // 10MB max
        'videos.*' => 'nullable|mimes:mp4,mov,avi|max:51200', // 50MB max
        'video_links.*' => 'nullable|url',
        'classroom_id' => 'required|exists:classrooms,id',
        'folders.*' => 'nullable|string|max:255',
    ]);

    $material = Material::create([
        'title' => $request->title,
        'description' => $request->description,
        'classroom_id' => $request->classroom_id,
        'teacher_id' => auth()->id(),
    ]);

    // Upload regular files
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $index => $file) {
            $folder = $request->folders[$index] ?? null;
            $ext = $file->getClientOriginalExtension();
            $material->files()->create([
                'file_path' => $file->store('materials', 'public'),
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $ext,
                'folder' => $folder,
            ]);
        }
    }

    // Upload videos
    if ($request->hasFile('videos')) {
        foreach ($request->file('videos') as $index => $video) {
            $folder = $request->folders[$index] ?? null;
            $material->files()->create([
                'file_path' => $video->store('videos', 'public'),
                'original_name' => $video->getClientOriginalName(),
                'file_type' => 'video',
                'folder' => $folder,
            ]);
        }
    }

    // Save video links
if ($request->filled('video_links')) {
    foreach ($request->video_links as $link) {
        if ($link) {
            $material->files()->create([
                'file_path' => null,            // No file uploaded
                'original_name' => $link,       // Show the link as the name
                'file_type' => 'link',
                'folder' => null,
                'link_url' => $link,            // Save the URL here
            ]);
        }
    }
}
    return redirect()->route('classes.materials', $request->classroom_id)
                     ->with('success', 'Material uploaded successfully!');
}

    /* ===========================
       DOWNLOAD SINGLE FILE
    ============================ */
public function download($id)
{
    $file = MaterialFile::findOrFail($id);

    if($file->file_type === 'link') {
        return redirect($file->link_url); // For links, just redirect
    }

    return response()->download(storage_path('app/public/' . $file->file_path), $file->original_name);
}

public function view($id)
{
    $file = MaterialFile::findOrFail($id);

    if($file->file_type === 'link') {
        return redirect($file->link_url);
    }

    $path = storage_path('app/public/' . $file->file_path);

    return response()->file($path); // Opens in browser
}

public function downloadAll($id)
{
    $material = Material::with('files')->findOrFail($id);

    if ($material->files->isEmpty()) {
        return back()->with('error', 'No files to download.');
    }

    $zipFileName = $material->title . '.zip';
    $zipPath = storage_path('app/public/temp/' . $zipFileName);

    // Ensure temp folder exists
    if (!file_exists(storage_path('app/public/temp'))) {
        mkdir(storage_path('app/public/temp'), 0777, true);
    }

    $zip = new \ZipArchive();
    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
        foreach ($material->files as $file) {
            $filePath = storage_path('app/public/' . $file->file_path);
            if (file_exists($filePath)) {
                // Add to ZIP with original filename
                $zip->addFile($filePath, $file->original_name);
            }
        }
        $zip->close();
    }

    return response()->download($zipPath)->deleteFileAfterSend(true);
}

// MaterialController.php
public function redirectLink($id)
{
    $file = MaterialFile::findOrFail($id);

    if ($file->file_type !== 'link' || !$file->link_url) {
        abort(404);
    }

    $youtubeId = null;
    if (Str::contains($file->link_url, 'youtube.com/watch?v=')) {
        parse_str(parse_url($file->link_url, PHP_URL_QUERY), $query);
        $youtubeId = $query['v'] ?? null;
    } elseif (Str::contains($file->link_url, 'youtu.be/')) {
        $youtubeId = last(explode('/', $file->link_url));
    }

    return view('materials.redirectLink', compact('file', 'youtubeId'));
}

    /* ===========================
       DELETE MATERIAL (TEACHER)
    ============================ */
    public function destroy($id)
    {
        $material = Material::with('files')->findOrFail($id);

        if ($material->teacher_id !== Auth::id()) {
            abort(403);
        }

        foreach ($material->files as $file) {
            // Only delete physical files if file_path exists
            if ($file->file_path) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete DB record
            $file->delete();
        }

        $material->delete();

        return back()->with('success', 'Material deleted successfully.');
    }

}
