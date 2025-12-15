<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'title_en',
        'url',
        'route_name',
        'route_params',
        'target',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'route_params' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the menu this item belongs to
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the parent menu item
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get all child menu items
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Check if item has children
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get the full URL for this menu item
     */
    public function getUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->route_name) {
            $params = $this->route_params ?? [];
            return route($this->route_name, $params);
        }

        return '#';
    }

    /**
     * Get the translated title based on current locale
     */
    public function getTranslatedTitle()
    {
        $locale = app()->getLocale();
        
        if ($locale === 'en' && $this->title_en) {
            return $this->title_en;
        }
        
        return $this->title;
    }
}
