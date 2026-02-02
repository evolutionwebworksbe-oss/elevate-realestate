<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\ObjectType;
use App\Models\District;
use App\Models\Omgeving;
use App\Models\ObjectSubType;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function sale()
    {
        return $this->indexWithFilter('sale');
    }

    public function rent()
    {
        return $this->indexWithFilter('rent');
    }

    public function corporate()
    {
        return $this->indexWithFilter('corporate');
    }

    public function byOwner()
    {
        return $this->indexWithFilter('by-owner');
    }

    private function indexWithFilter($type)
    {
        $query = Property::with(['district', 'objectSubType', 'currencyRelation', 'images', 'objectType']);

        $currentObjectType = null;
        $currentObjectSubType = null;
        $locale = app()->getLocale();
        
        // Translation mappings
        $typeTranslations = [
            'Te Koop' => $locale == 'en' ? 'For Sale' : 'Te Koop',
            'Te Huur' => $locale == 'en' ? 'For Rent' : 'Te Huur',
            'Corporate' => 'Corporate',
            'Door Eigenaar' => $locale == 'en' ? 'By Owner' : 'Door Eigenaar',
        ];
        
        $subtypeTranslations = [
            'Woningen' => $locale == 'en' ? 'Houses' : 'Woningen',
            'Percelen' => $locale == 'en' ? 'Lots' : 'Percelen',
            'Panden' => $locale == 'en' ? 'Buildings' : 'Panden',
            'Appartementen' => $locale == 'en' ? 'Apartments' : 'Appartementen',
            'Kantoren' => $locale == 'en' ? 'Offices' : 'Kantoren',
            'Bar/Restaurant' => 'Bar/Restaurant',
            'Kantoor met werkloods' => $locale == 'en' ? 'Office with Warehouse' : 'Kantoor met werkloods',
        ];
        
        // Apply filters based on type
        switch ($type) {
            case 'sale':
                $currentObjectType = ObjectType::find(2); // Te Koop
                if ($currentObjectType) {
                    $query->where('objectType_id', $currentObjectType->id);
                }
                $title = $typeTranslations['Te Koop'];
                break;
            case 'rent':
                $currentObjectType = ObjectType::find(3); // Te Huur
                if ($currentObjectType) {
                    $query->where('objectType_id', $currentObjectType->id);
                }
                $title = $typeTranslations['Te Huur'];
                break;
            case 'corporate':
                $query->where('corporate', 2);
                $title = 'Corporate';
                break;
            case 'by-owner':
                $query->where('byowner', 1);
                $title = $typeTranslations['Door Eigenaar'];
                break;
        }

        // Handle submenu object_subtype parameter - MATCH BY OBJECT TYPE TOO
        if (request('object_subtype') && $currentObjectType) {
            $subtypeMap = [
                'woningen' => 'Woningen',
                'panden' => 'Panden',
                'percelen' => 'Percelen',
                'appartementen' => 'Appartementen',
                'kantoren' => 'Kantoren',
                'bar-restaurant' => 'Bar/Restaurant',
                'kantoor-werkloods' => 'Kantoor met werkloods',
            ];
            
            $subtypeSlug = request('object_subtype');
            
            if (isset($subtypeMap[$subtypeSlug])) {
                // Find subtype that matches BOTH name AND object type
                $subtype = ObjectSubType::where('naam', $subtypeMap[$subtypeSlug])
                    ->where('objectType_id', $currentObjectType->id)
                    ->first();
                    
                if ($subtype) {
                    $query->where('objectSubType_id', $subtype->id);
                    $currentObjectSubType = $subtype;
                }
            }
        }

        // Handle by-owner sub-filters
        if ($type == 'by-owner' && request('type')) {
            if (request('type') == 'koop') {
                $currentObjectType = ObjectType::find(2);
                if ($currentObjectType) {
                    $query->where('objectType_id', $currentObjectType->id);
                    $title = $typeTranslations['Door Eigenaar'] . ' - ' . $typeTranslations['Te Koop'];
                }
            } elseif (request('type') == 'huur') {
                $currentObjectType = ObjectType::find(3);
                if ($currentObjectType) {
                    $query->where('objectType_id', $currentObjectType->id);
                    $title = $typeTranslations['Door Eigenaar'] . ' - ' . $typeTranslations['Te Huur'];
                }
            }
        }

        // Handle corporate sub-filters
        if ($type == 'corporate' && request('type')) {
            $corporateType = request('type');
            
            if (str_contains($corporateType, 'huur')) {
                $currentObjectType = ObjectType::find(3);
                if ($currentObjectType) {
                    $query->where('objectType_id', $currentObjectType->id);
                }
                
                if (str_contains($corporateType, 'woningen')) {
                    $subtype = ObjectSubType::where('naam', 'Woningen')->where('objectType_id', 3)->first();
                    if ($subtype) {
                        $query->where('objectSubType_id', $subtype->id);
                        $currentObjectSubType = $subtype;
                        $title = 'Corporate - ' . ($locale == 'en' ? 'Rental Houses' : 'Huurwoningen');
                    }
                } elseif (str_contains($corporateType, 'panden')) {
                    $subtype = ObjectSubType::where('naam', 'Panden')->where('objectType_id', 3)->first();
                    if ($subtype) {
                        $query->where('objectSubType_id', $subtype->id);
                        $currentObjectSubType = $subtype;
                        $title = 'Corporate - ' . ($locale == 'en' ? 'Rental Buildings' : 'Huurpanden');
                    }
                } elseif (str_contains($corporateType, 'kantoren')) {
                    $subtype = ObjectSubType::where('naam', 'Kantoren')->where('objectType_id', 3)->first();
                    if ($subtype) {
                        $query->where('objectSubType_id', $subtype->id);
                        $currentObjectSubType = $subtype;
                        $title = 'Corporate - ' . ($locale == 'en' ? 'Rental Offices' : 'Huurkantoren');
                    }
                }
            } elseif (str_contains($corporateType, 'koop')) {
                $currentObjectType = ObjectType::find(2);
                if ($currentObjectType) {
                    $query->where('objectType_id', $currentObjectType->id);
                }
                
                if (str_contains($corporateType, 'woningen')) {
                    $subtype = ObjectSubType::where('naam', 'Woningen')->where('objectType_id', 2)->first();
                    if ($subtype) {
                        $query->where('objectSubType_id', $subtype->id);
                        $currentObjectSubType = $subtype;
                        $title = 'Corporate - ' . ($locale == 'en' ? 'Houses for Sale' : 'Koopwoningen');
                    }
                } elseif (str_contains($corporateType, 'panden')) {
                    $subtype = ObjectSubType::where('naam', 'Panden')->where('objectType_id', 2)->first();
                    if ($subtype) {
                        $query->where('objectSubType_id', $subtype->id);
                        $currentObjectSubType = $subtype;
                        $title = 'Corporate - ' . ($locale == 'en' ? 'Buildings for Sale' : 'Kooppanden');
                    }
                }
            }
        }

        // Apply additional filters from form (ID-based)
        if (request('object_type') && is_numeric(request('object_type'))) {
            $query->where('objectType_id', request('object_type'));
            $currentObjectType = ObjectType::find(request('object_type'));
        }

        if (request('object_subtype_id') && is_numeric(request('object_subtype_id'))) {
            $query->where('objectSubType_id', request('object_subtype_id'));
            $currentObjectSubType = ObjectSubType::find(request('object_subtype_id'));
        }

        if (request('district_id')) {
            $query->where('district_id', request('district_id'));
        }

        if (request('omgeving_id')) {
            $query->where('omgeving_id', request('omgeving_id'));
        }

        if (request('min_price')) {
            $query->where('vraagPrijs', '>=', request('min_price'));
        }

        if (request('max_price')) {
            $query->where('vraagPrijs', '<=', request('max_price'));
        }

        if (request('bedrooms')) {
            $query->where('aantalSlaapkamers', '>=', request('bedrooms'));
        }

        if (request('bathrooms')) {
            $query->where('aantalBadkamers', '>=', request('bathrooms'));
        }

        if (request('min_surface')) {
            $query->where('woonOppervlakte', '>=', request('min_surface'));
        }

        if (request('max_surface')) {
            $query->where('woonOppervlakte', '<=', request('max_surface'));
        }

        if (request('titel')) {
            $query->where('titel_id', request('titel'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('furnished')) {
            $query->where('gemeubileerd', 1);
        }

        $properties = $query->paginate(12);

        // Build dynamic title with translated subtype
        $titleParts = [$title];
        
        if ($currentObjectSubType) {
            $subtypeName = $currentObjectSubType->naam;
            $translatedSubtype = $subtypeTranslations[$subtypeName] ?? $subtypeName;
            $titleParts[] = $translatedSubtype;
        }

        $title = implode(' - ', $titleParts);

        $objectTypes = ObjectType::orderBy('naam')->get();
        $objectSubTypes = ObjectSubType::orderBy('naam')->get();
        $districts = District::orderBy('naam')->get();

        return view('properties.index', compact(
            'properties', 
            'title', 
            'objectTypes', 
            'objectSubTypes', 
            'districts', 
            'type',
            'currentObjectType',
            'currentObjectSubType'
        ));
    }

    public function search(Request $request)
    {
        $query = Property::with(['district', 'objectSubType', 'currencyRelation', 'images', 'objectType']);
    
        $currentObjectType = null;
        $currentObjectSubType = null;
        $currentDistrict = null;
        $currentOmgeving = null;
    
        // Search by property name (primary search)
        if ($request->q || $request->search_name) {
            $searchTerm = $request->q ?? $request->search_name;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('titel', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('address', 'LIKE', '%' . $searchTerm . '%');
            });
        }
    
        // Apply filters
        if ($request->object_type) {
            $query->where('objectType_id', $request->object_type);
            $currentObjectType = \App\Models\ObjectType::find($request->object_type);
        }
    
        if ($request->object_subtype_id) {
            $query->where('objectSubType_id', $request->object_subtype_id);
            $currentObjectSubType = \App\Models\ObjectSubType::find($request->object_subtype_id);
        }
    
        if ($request->district_id) {
            $query->where('district_id', $request->district_id);
            $currentDistrict = \App\Models\District::find($request->district_id);
        }
    
        if ($request->omgeving_id) {
            $query->where('omgeving_id', $request->omgeving_id);
            $currentOmgeving = \App\Models\Omgeving::find($request->omgeving_id);
        }
    
        if ($request->min_price) {
            $query->where('vraagPrijs', '>=', $request->min_price);
        }
    
        if ($request->max_price) {
            $query->where('vraagPrijs', '<=', $request->max_price);
        }
    
        if ($request->bedrooms) {
            $query->where('aantalSlaapkamers', '>=', $request->bedrooms);
        }
    
        if ($request->bathrooms) {
            $query->where('aantalBadkamers', '>=', $request->bathrooms);
        }
    
        if ($request->min_surface) {
            $query->where('woonOppervlakte', '>=', $request->min_surface);
        }
    
        if ($request->max_surface) {
            $query->where('woonOppervlakte', '<=', $request->max_surface);
        }
    
        if ($request->titel) {
            $query->where('titel_id', $request->titel);
        }
    
        if ($request->status) {
            $query->where('status', $request->status);
        }
    
        if ($request->furnished) {
            $query->where('gemeubileerd', 1);
        }
    
        $properties = $query->paginate(12);
    
        // Build dynamic title
        $titleParts = [];
        
        $searchTerm = $request->q ?? $request->search_name;
        if ($searchTerm) {
            $titleParts[] = 'Zoeken: "' . $searchTerm . '"';
        }
        
        if ($currentObjectType) {
            $titleParts[] = $currentObjectType->naam;
        }
        
        if ($currentObjectSubType) {
            $titleParts[] = $currentObjectSubType->naam;
        }
        
        if ($currentDistrict) {
            $titleParts[] = $currentDistrict->naam;
        }
        
        if ($currentOmgeving) {
            $titleParts[] = $currentOmgeving->naam;
        }
        
        $title = !empty($titleParts) ? implode(' - ', $titleParts) : 'Zoekresultaten';
    
        $objectTypes = \App\Models\ObjectType::orderBy('naam')->get();
        $objectSubTypes = \App\Models\ObjectSubType::orderBy('naam')->get();
        $districts = \App\Models\District::orderBy('naam')->get();
        
        $type = 'search';
    
        return view('properties.index', compact(
            'properties', 
            'title', 
            'objectTypes', 
            'objectSubTypes', 
            'districts', 
            'type',
            'currentObjectType',
            'currentObjectSubType'
        ));
    }

    // Live search API endpoint
    public function liveSearch(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Search by property name (naam field - same for both languages)
        // Removed status filter to show all properties (even sold/rented)
        $properties = Property::where(function ($q) use ($query) {
                $q->where('naam', 'LIKE', '%' . $query . '%')
                  ->orWhere('address', 'LIKE', '%' . $query . '%');
            })
            ->with(['district', 'currencyRelation', 'images'])
            ->limit(10)
            ->get()
            ->map(function($property) {
                $locale = app()->getLocale();
                // Use naam as the primary display title since that's what we're searching
                $title = $property->naam ?: ($locale == 'nl' ? $property->titel : ($property->titel_en ?? $property->titel));
                
                // Get the featured image or first image
                $imageUrl = null;
                if ($property->featuredFoto) {
                    $imageUrl = asset('portal/' . $property->featuredFoto);
                } elseif ($property->images->first()) {
                    $imageUrl = asset('portal/uploads/objecten/' . $property->images->first()->foto);
                }
                
                return [
                    'id' => $property->id,
                    'title' => $title,
                    'slug' => $property->slug,
                    'price' => number_format($property->vraagPrijs, 0, ',', '.'),
                    'currency' => $property->currencyRelation->afkorting ?? '',
                    'district' => $property->district->naam ?? '',
                    'address' => $property->address,
                    'image' => $imageUrl,
                    'url' => route('properties.show', $property->slug)
                ];
            });

        return response()->json($properties);
    }

    public function show(Property $property)
    {
        $property->load([
            'district',
            'omgeving',
            'objectSubType.objectType',
            'images',
            'teamMembers.titleTypes',
            'currencyRelation',
            'titel',
            'details',
            'voorzieningen',
            'beveiliging',
            'extraRuimtes'
        ]);

        // Get similar properties
        $similarProperties = Property::where('id', '!=', $property->id)
            ->where('status', 1)
            ->where(function($q) use ($property) {
                $q->where('district_id', $property->district_id)
                  ->orWhere('objectSubType_id', $property->objectSubType_id);
            })
            ->with(['district', 'objectSubType', 'currencyRelation'])
            ->limit(4)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }
}
