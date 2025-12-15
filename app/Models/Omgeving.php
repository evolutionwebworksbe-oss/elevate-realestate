<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Omgeving extends Model
{
    protected $table = 'omgevingen';
    public $timestamps = false;
    
    protected $fillable = ['naam', 'district_id'];
    
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    
    public function properties()
    {
        return $this->hasMany(Property::class, 'omgeving_id');
    }
}