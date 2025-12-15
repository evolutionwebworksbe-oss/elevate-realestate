<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'objecten';
    public $timestamps = true;
    
    protected $fillable = [
        'naam',
        'slug',
        'naam_en', 
        'vraagPrijs',
        'discount',
        'currency',
        'district_id',
        'omgeving_id',
        'objectType_id',
        'objectSubType_id',
        'status',
        'titel_id',
        'aantalSlaapkamers',
        'aantalBadkamers',
        'woonOppervlakte',
        'perceelOppervlakte',
        'oppervlakteEenheid',
        'gemeubileerd',
        'huurwaarborg',
        'beschikbaarheid',
        'omschrijving',
        'omschrijving_en',
        'youtube',
        'directions',
        'corporate',
        'country',
        'byowner',
        'featured',
        'featuredFoto',
        'agent'
    ];

    // Relationships
    public function details()
    {
        return $this->hasOne(PropertyDetail::class, 'property_id');
    }
    
    public function objectType() { 
        return $this->belongsTo(ObjectType::class, 'objectType_id'); 
    }

    public function voorzieningen()
    {
        return $this->belongsToMany(Voorziening::class, 'property_voorzieningen', 'property_id', 'voorziening_id');
    }
    
    public function beveiliging()
    {
        return $this->belongsToMany(BeveiligingType::class, 'property_beveiliging', 'property_id', 'beveiliging_type_id');
    }
    
    public function extraRuimtes()
    {
        return $this->belongsToMany(ExtraRuimteType::class, 'property_extra_ruimtes', 'property_id', 'extra_ruimte_type_id');
    }
    
    public function district()
    {
         return $this->belongsTo(District::class, 'district_id'); 
    }

    public function omgeving() 
    { 
        return $this->belongsTo(Omgeving::class, 'omgeving_id'); 
    }

    public function objectSubType() 
    { 
        return $this->belongsTo(ObjectSubType::class, 'objectSubType_id'); 
    }

    public function images() 
    { 
        return $this->hasMany(PropertyImage::class, 'object_id')->orderBy('display_order'); 
    }

    public function teamMembers() 
    { 
        return $this->belongsToMany(TeamMember::class, 'objects_team', 'object', 'team_member'); 
    }

    public function currencyRelation() 
    { 
        return $this->belongsTo(Currency::class, 'currency', 'id'); 
    }

    public function titel() 
    { 
        return $this->belongsTo(Titel::class, 'titel_id'); 
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property->naam);
            }
        });
        
        static::updating(function ($property) {
            if ($property->isDirty('naam')) {
                $property->slug = static::generateUniqueSlug($property->naam, $property->id);
            }
        });
    }
    
    // Helper method to generate unique slug
    protected static function generateUniqueSlug($name, $id = null)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (static::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
    
    // Tell Laravel to use slug for routes
    public function getRouteKeyName()
    {
        return 'slug';
    }
}