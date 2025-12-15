<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryDisplaySetting extends Model
{
    protected $fillable = [
        'category_type',
        'subtype_key',
        'display_order'
    ];
    
    /**
     * Get display order for a specific category/subtype
     */
    public static function getOrder($categoryType, $subtypeKey)
    {
        $setting = static::where('category_type', $categoryType)
            ->where('subtype_key', $subtypeKey)
            ->first();
            
        return $setting ? $setting->display_order : 999; // Default to end if not set
    }
    
    /**
     * Set display order for a category/subtype
     */
    public static function setOrder($categoryType, $subtypeKey, $order)
    {
        return static::updateOrCreate(
            [
                'category_type' => $categoryType,
                'subtype_key' => $subtypeKey
            ],
            [
                'display_order' => $order
            ]
        );
    }
}
