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

        $class = Classroom::findOrFail($classId);

        return view('classes.materials', compact('materials', 'class'));
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
       STORE MATERIAL
    ============================ */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files.*' => 'nullable|file|max:10240',
            'videos.*' => 'nullable|mimes:mp4,mov,avi|max:614400',
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
                $material->files()->create([
                    'file_path' => $file->store('materials', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
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
                        'file_path' => null,
                        'original_name' => $link,
                        'file_type' => 'link',
                        'folder' => null,
                        'link_url' => $link,
                    ]);
                }
            }
        }

        // ✅ Activity log
        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'material_id' => $material->id,
                'class_id' => $material->classroom_id,
                'title' => $material->title,
            ])
            ->log('Uploaded material/lesson');

        return redirect()->route('classes.materials', $request->classroom_id)
                         ->with('success', 'Material uploaded successfully!');
    }

    /* ===========================
       DOWNLOAD SINGLE FILE
    ============================ */
    public function download($id)
    {
        $file = MaterialFile::findOrFail($id);

        if ($file->file_type === 'link') {
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'file_id' => $file->id,
                    'link_url' => $file->link_url,
                ])
                ->log('Accessed material link');

            return redirect($file->link_url);
        }

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'file_id' => $file->id,
                'file_name' => $file->original_name,
            ])
            ->log('Downloaded material/file');

        return response()->download(storage_path('app/public/' . $file->file_path), $file->original_name);
    }

    /* ===========================
       DOWNLOAD ALL FILES
    ============================ */
    public function downloadAll($id)
    {
        $material = Material::with('files')->findOrFail($id);

        $files = $material->files->filter(fn ($f) => $f->file_type !== 'link');

        if ($files->isEmpty()) {
            return back()->with('error', 'No files to download.');
        }

        $zipDir = storage_path('app/public/temp');
        if (!file_exists($zipDir)) mkdir($zipDir, 0755, true);

        $zipFileName = Str::slug($material->title) . '.zip';
        $zipPath = $zipDir . '/' . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) $zip->addFile($filePath, $file->original_name);
            }
            $zip->close();
        } else {
            return back()->with('error', 'Failed to create ZIP file.');
        }

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'material_id' => $material->id,
                'file_count' => $files->count(),
            ])
            ->log('Downloaded all files as ZIP');

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /* ===========================
       REDIRECT LINK (e.g., YouTube)
    ============================ */
    public function redirectLink($id)
    {
        $file = MaterialFile::findOrFail($id);
        if ($file->file_type !== 'link' || !$file->link_url) abort(404);

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'file_id' => $file->id,
                'link_url' => $file->link_url,
            ])
            ->log('Accessed material link');

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
       EDIT MATERIAL
    ============================ */
    public function edit($id)
    {
        $material = Material::with('files')->findOrFail($id);
        if ($material->teacher_id !== auth()->id()) abort(403);

        $classroomId = $material->classroom_id;
        return view('teacher.materials.edit', compact('material', 'classroomId'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files.*' => 'nullable|file|max:10240',
            'videos.*' => 'nullable|mimes:mp4,mov,avi|max:614400',
            'video_links.*' => 'nullable|url',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $material = Material::with('files')->findOrFail($id);
        if ($material->teacher_id !== auth()->id()) abort(403);

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $keepIds = $request->input('keep_existing_files', []);
        foreach ($material->files as $file) {
            if (!in_array($file->id, $keepIds)) {
                if ($file->file_type !== 'link' && $file->file_path) {
                    Storage::disk('public')->delete($file->file_path);
                }
                $file->delete();
            }
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $material->files()->create([
                    'file_path' => $file->store('materials', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $material->files()->create([
                    'file_path' => $video->store('videos', 'public'),
                    'original_name' => $video->getClientOriginalName(),
                    'file_type' => 'video',
                ]);
            }
        }

        if ($request->video_links) {
            foreach ($request->video_links as $link) {
                if ($link) {
                    $material->files()->create([
                        'file_type' => 'link',
                        'original_name' => $link,
                        'link_url' => $link,
                    ]);
                }
            }
        }

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'material_id' => $material->id,
                'class_id' => $material->classroom_id,
                'title' => $material->title,
            ])
            ->log('Updated material/lesson');

        return redirect()->route('classes.materials', $request->classroom_id)
                         ->with('success', 'Material updated successfully!');
    }

    /* ===========================
       DELETE MATERIAL
    ============================ */
    public function destroy($id)
    {
        $material = Material::with('files')->findOrFail($id);
        if ($material->teacher_id !== Auth::id()) abort(403);

        foreach ($material->files as $file) {
            if ($file->file_path) Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'material_id' => $material->id,
                'class_id' => $material->classroom_id,
                'title' => $material->title,
            ])
            ->log('Deleted material/lesson');

        $material->delete();

        return back()->with('success', 'Material deleted successfully.');
    }
}
