<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\TeamMember;
use App\Models\Newsletter;
use App\Models\District;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $propertiesCount = Property::count();
        $teamMembersCount = TeamMember::count();
        $activeListings = Property::where('status', 1)->count();
        
        // Property breakdown by status
        $availableProperties = Property::where('status', 1)->count();
        $soldRentedProperties = Property::where('status', 2)->count();
        $reservedProperties = Property::where('status', 3)->count();
        
        // Properties by type
        $propertiesForSale = Property::where('status', 1)
            ->whereHas('objectType', function($q) {
                $q->where('naam', 'LIKE', '%koop%');
            })->count();
            
        $propertiesForRent = Property::where('status', 1)
            ->whereHas('objectType', function($q) {
                $q->where('naam', 'LIKE', '%huur%');
            })->count();
        
        // Recent activity
        $recentProperties = Property::with(['district', 'objectSubType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // This month's stats
        $propertiesThisMonth = Property::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
            
        // Newsletter subscribers
        $newsletterCount = Newsletter::count();
        
        // Featured properties count
        $featuredCount = Property::where('featured', 1)->where('status', 1)->count();
        
        // Districts count
        $districtsCount = District::count();
        
        // Total property images
        $totalImages = DB::table('objectFotos')->count();
        
        return view('admin.dashboard', [
            'propertiesCount' => $propertiesCount,
            'teamMembersCount' => $teamMembersCount,
            'activeListings' => $activeListings,
            'availableProperties' => $availableProperties,
            'soldRentedProperties' => $soldRentedProperties,
            'reservedProperties' => $reservedProperties,
            'propertiesForSale' => $propertiesForSale,
            'propertiesForRent' => $propertiesForRent,
            'recentProperties' => $recentProperties,
            'propertiesThisMonth' => $propertiesThisMonth,
            'newsletterCount' => $newsletterCount,
            'featuredCount' => $featuredCount,
            'districtsCount' => $districtsCount,
            'totalImages' => $totalImages,
        ]);
    }
}