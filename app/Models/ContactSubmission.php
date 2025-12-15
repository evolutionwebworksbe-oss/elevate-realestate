<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'property_id',
        'submitted_at'
    ];
    
    protected $casts = [
        'submitted_at' => 'datetime',
    ];
    
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}