<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    protected $table = 'team';
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'slug',
        'title',
        'phone',
        'whatsapp',
        'email',
        'image',
        'description',
        'display_order',
        'show_as_agent'
    ];

    protected $casts = [
        'show_as_agent' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($teamMember) {
            if (empty($teamMember->slug)) {
                $teamMember->slug = Str::slug($teamMember->name);
            }
        });
        
        static::updating(function ($teamMember) {
            if ($teamMember->isDirty('name') && empty($teamMember->slug)) {
                $teamMember->slug = Str::slug($teamMember->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'objects_team', 'team_member', 'object');
    }

    // Old single title relationship (kept for backward compatibility)
    public function titleType()
    {
        return $this->belongsTo(TeamTitleType::class, 'title');
    }

    // New multiple titles relationship
    public function titleTypes()
    {
        return $this->belongsToMany(TeamTitleType::class, 'team_member_titles', 'team_member_id', 'team_title_type_id');
    }

    // Helper to get all titles (combines old and new)
    public function getAllTitles()
    {
        $titles = $this->titleTypes;
        
        // If no titles in new table but has old title field, include it
        if ($titles->isEmpty() && $this->title && $this->titleType) {
            $titles = collect([$this->titleType]);
        }
        
        return $titles;
    }
}
