<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    protected $table = 'objectFotos';
    public $timestamps = false;
    
    protected $fillable = ['object_id', 'url', 'display_order', 'alt_text'];
}