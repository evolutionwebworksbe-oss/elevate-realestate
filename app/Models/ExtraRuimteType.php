<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraRuimteType extends Model
{
    protected $table = 'extra_ruimte_types';
    public $timestamps = false;
    
    protected $fillable = ['naam'];
    
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_extra_ruimtes', 'extra_ruimte_type_id', 'property_id');
    }
}