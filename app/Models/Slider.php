<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'image',
        'order',
        'active'
    ];
}