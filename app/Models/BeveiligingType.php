<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeveiligingType extends Model
{
    protected $table = 'beveiliging_types';
    public $timestamps = false;
    
    protected $fillable = ['naam'];
    
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_beveiliging', 'beveiliging_type_id', 'property_id');
    }
}