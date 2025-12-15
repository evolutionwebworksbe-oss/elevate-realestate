<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\ObjectType;
use App\Models\District;
use App\Models\Omgeving;  // ADD THIS LINE
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active sliders
        $sliders = \App\Models\Slider::where('active', 1)
            ->orderBy('order')
            ->get();

        // Get recent properties
        $recentProperties = Property::where('status', 1)
            ->with(['district', 'objectSubType', 'currencyRelation', 'objectType'])
            ->orderBy('id', 'desc')
            ->limit(9)
            ->get();

        // Get featured properties ONLY if there are 4 or more
        $featuredCount = Property::where('featured', 1)->where('status', 1)->count();
        
        $featuredProperties = collect([]);
        if ($featuredCount >= 4) {
            $featuredProperties = Property::where('featured', 1)
                ->where('status', 1)
                ->with(['district', 'objectSubType', 'currencyRelation', 'objectType'])
                ->orderBy('id', 'desc')
                ->limit(6)
                ->get();
        }

        $objectTypes = ObjectType::orderBy('naam')->get();
        $districts = District::orderBy('naam')->get();
        $omgevingen = Omgeving::orderBy('naam')->get();

        $stats = [
            'total_properties' => Property::where('status', 1)->count(),
            'properties_for_sale' => Property::where('status', 1)
                ->whereHas('objectType', function($q) {
                    $q->where('naam', 'LIKE', '%koop%');
                })->count(),
            'properties_for_rent' => Property::where('status', 1)
                ->whereHas('objectType', function($q) {
                    $q->where('naam', 'LIKE', '%huur%');
                })->count(),
            'happy_clients' => 500, // You can make this dynamic later
        ];

        return view('home', compact('sliders', 'featuredProperties', 'recentProperties', 'objectTypes', 'districts', 'omgevingen', 'stats'));
    }
}