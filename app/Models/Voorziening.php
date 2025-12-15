<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voorziening extends Model
{
    protected $table = 'voorzieningen';
    public $timestamps = false;
    
    protected $fillable = ['naam'];
    
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_voorzieningen', 'voorziening_id', 'property_id');
    }
}