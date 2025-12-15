<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectSubType extends Model
{
    protected $table = 'objectSubTypes';
    public $timestamps = false;
    
    protected $fillable = ['naam', 'objectType_id', 'display_order'];
    
    public function objectType()
    {
        return $this->belongsTo(ObjectType::class, 'objectType_id');
    }
    
    public function properties()
    {
        return $this->hasMany(Property::class, 'objectSubType_id');
    }
}