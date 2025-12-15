<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectType extends Model
{
    protected $table = 'objectTypes';
    public $timestamps = false;
    
    protected $fillable = ['naam'];
    
    public function subTypes()
    {
        return $this->hasMany(ObjectSubType::class, 'objectType_id');
    }
}