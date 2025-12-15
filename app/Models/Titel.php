<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Titel extends Model
{
    protected $table = 'titels';
    public $timestamps = false;
    
    protected $fillable = ['naam'];
    
    public function properties()
    {
        return $this->hasMany(Property::class, 'titel_id');
    }
}