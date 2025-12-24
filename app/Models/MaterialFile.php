<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'original_name',
        'file_path', // local storage path
        'file_type', // 'video', 'pdf', 'image', 'link'
        'link_url',  // optional for video links
        'folder',
    ];




    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
