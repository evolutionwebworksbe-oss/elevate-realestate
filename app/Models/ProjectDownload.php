<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDownload extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'label', 'file_path', 'external_url', 'display_order'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getDownloadUrl(): string
    {
        if ($this->external_url) {
            return $this->external_url;
        }
        return asset('portal/projects/downloads/' . $this->file_path);
    }
}
