<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_nl', 'title_en', 'slug',
        'excerpt_nl', 'excerpt_en',
        'description_nl', 'description_en',
        'featured_image', 'project_type_id', 'status',
        'location', 'total_units', 'total_area',
        'start_date', 'end_date',
        'is_featured', 'is_published',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    public static $statuses = [
        'ongoing'    => 'Ongoing',
        'completed'  => 'Completed',
        'coming_soon'=> 'Coming Soon',
        'planning'   => 'Planning',
    ];

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('display_order');
    }

    public function videos()
    {
        return $this->hasMany(ProjectVideo::class)->orderBy('display_order');
    }

    public function downloads()
    {
        return $this->hasMany(ProjectDownload::class)->orderBy('display_order');
    }

    public function getTranslatedTitle(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && $this->title_en) {
            return $this->title_en;
        }
        return $this->title_nl;
    }

    public function getTranslatedExcerpt(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && $this->excerpt_en) {
            return $this->excerpt_en;
        }
        return $this->excerpt_nl;
    }

    public function getTranslatedDescription(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && $this->description_en) {
            return $this->description_en;
        }
        return $this->description_nl;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statuses[$this->status] ?? $this->status;
    }

    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
