<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_nl',
        'title_en',
        'slug',
        'content_nl',
        'content_en',
        'meta_title',
        'meta_description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the title based on the current locale.
     */
    public function getTranslatedTitle(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en' && $this->title_en) {
            return $this->title_en;
        }

        return $this->title_nl;
    }

    /**
     * Get the content based on the current locale.
     */
    public function getTranslatedContent(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'en' && $this->content_en) {
            return $this->content_en;
        }

        return $this->content_nl;
    }

    /**
     * Generate a unique slug from a title.
     */
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
