<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyDetail extends Model
{
    protected $table = 'property_details';
    
    protected $fillable = [
        'property_id',
        'woonlagen',
        'woonkamer_count',
        'keuken_count',
        'toiletten_count',
        'parkeergelegenheid_type',
        'parkeerplaatsen_aantal',
        'airco_algemeen',
        'airco_locaties'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}