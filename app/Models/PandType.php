<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PandType extends Model
{
    protected $table = 'pandTypes';
    public $timestamps = false;

    protected $fillable = ['naam'];
}
