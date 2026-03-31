<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectVideo extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'url', 'title', 'display_order'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getEmbedUrl(): string
    {
        // Convert various YouTube URL formats to embed URL
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->url, $matches);
        if (!empty($matches[1])) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        return $this->url;
    }
}
