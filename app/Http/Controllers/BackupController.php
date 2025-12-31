<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = collect(Storage::disk('local')->files('backups')) // <-- specify path
            ->filter(fn($file) => str_ends_with($file, '.zip'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => Storage::disk('local')->size($file) / 1024 / 1024,
                    'last_modified' => date('Y-m-d H:i:s', Storage::disk('local')->lastModified($file)),
                    'path' => $file,
                    'status' => 'success',
                ];
            })->sortByDesc('last_modified');

        $latestBackup = $backups->first();
        $status = $latestBackup ? 'success' : 'failed';
        $lastBackupTime = $latestBackup ? $latestBackup['last_modified'] : null;

        return view('admin.backups.index', compact('backups', 'status', 'lastBackupTime'));
    }




    public function runBackup()
    {
        try {
            Artisan::call('backup:run');
            return redirect()->back()->with('success', 'Backup completed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup failed: '.$e->getMessage());
        }
    }

    public function download($filename)
    {
        $path = storage_path("app/{$filename}");
        if (file_exists($path)) {
            return response()->download($path);
        }
        return redirect()->back()->with('error', 'Backup not found!');
    }

    public function delete($filename)
    {
        if (Storage::disk('local')->exists($filename)) {
            Storage::disk('local')->delete($filename);
            return redirect()->back()->with('success', 'Backup deleted successfully!');
        }
        return redirect()->back()->with('error', 'Backup not found!');
    }
}
