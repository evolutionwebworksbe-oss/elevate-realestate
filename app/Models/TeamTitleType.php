<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTitleType extends Model
{
    protected $table = 'team_title_type';
    public $timestamps = false;
    protected $fillable = ['name'];
}
