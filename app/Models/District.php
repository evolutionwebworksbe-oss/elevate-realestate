<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districten';
    public $timestamps = false;

    protected $fillable = ['naam'];
}