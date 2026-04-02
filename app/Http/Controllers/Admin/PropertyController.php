<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\District;
use App\Models\Omgeving;
use App\Models\ObjectType;
use App\Models\ObjectSubType;
use App\Models\Currency;
use App\Models\Titel;
use App\Models\Voorziening;
use App\Models\BeveiligingType;
use App\Models\PropertyImage;
use App\Models\ExtraRuimteType;
use App\Services\TranslationService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('currencyRelation', 'district', 'objectSubType');
        
        // Search by name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('naam', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by object type
        if ($request->filled('type')) {
            $query->where('objectType_id', $request->type);
        }
        
        // Filter by district
        if ($request->filled('district')) {
            $query->where('district_id', $request->district);
        }
        
        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('vraagPrijs', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('vraagPrijs', '<=', $request->max_price);
        }
        
        $properties = $query->orderBy('id', 'desc')->paginate(20);
        
        return view('admin.properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load([
            'district',
            'omgeving',
            'objectSubType.objectType',
            'images',
            'teamMembers.titleType',
            'currencyRelation',
            'titel',
            'details',
            'voorzieningen',
            'beveiliging',
            'extraRuimtes'
        ]);
        
        return view('admin.properties.show', compact('property'));
    }

    public function translate(Request $request, TranslationService $translator)
    {
        $validated = $request->validate([
            'text' => 'required|string'
        ]);
        
        try {
            $translation = $translator->translateToEnglish($validated['text']);
            
            return response()->json([
                'success' => true,
                'translation' => $translation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation failed'
            ], 500);
        }
    }
    
    public function create()
    {
        $districts = District::orderBy('naam')->get();
        $omgevingen = Omgeving::orderBy('naam')->get();
        $objectTypes = ObjectType::orderBy('naam')->get();
        $objectSubTypes = ObjectSubType::with('objectType')->orderBy('naam')->get();
        $currencies = Currency::orderBy('name')->get();
        $titels = Titel::orderBy('naam')->get();
        $voorzieningen = Voorziening::orderBy('naam')->get();
        $beveiligingTypes = BeveiligingType::orderBy('naam')->get();
        $extraRuimteTypes = ExtraRuimteType::orderBy('naam')->get();
        $teamMembers = \App\Models\TeamMember::with('titleType')->orderBy('name')->get();
        
        return view('admin.properties.create', compact(
            'districts',
            'omgevingen',
            'objectTypes',
            'objectSubTypes',
            'currencies',
            'titels',
            'voorzieningen',
            'beveiligingTypes',
            'extraRuimteTypes',
            'teamMembers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Info
            'naam' => 'required|string|max:50',
            'vraagPrijs' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'currency' => 'required|exists:currencies,id',
            'district_id' => 'required|exists:districten,id',
            'omgeving_id' => 'nullable|exists:omgevingen,id',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'objectSubType_id' => 'required|exists:objectSubTypes,id',
            'status' => 'required|in:1,2,3',
            'titel_id' => 'nullable|exists:titels,id',
            
            // Property Details
            'aantalSlaapkamers' => 'nullable|integer',
            'aantalBadkamers' => 'nullable|integer',
            'woonOppervlakte' => 'nullable|numeric',
            'perceelOppervlakte' => 'nullable|numeric',
            'oppervlakteEenheid' => 'nullable|in:1,2',
            
            // Existing fields in objecten table
            'gemeubileerd' => 'nullable|in:1,2',
            'huurwaarborg' => 'nullable|string|max:60',
            'beschikbaarheid' => 'nullable|string|max:60',
            
            // Description and Media
            'omschrijving' => 'nullable|string',
            'omschrijving_en' => 'nullable|string',
            'youtube' => 'nullable|string|max:255',
            'directions' => 'nullable|string|max:255',
            
            // Flags
            'byowner' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'corporate' => 'required|in:1,2',
            'country' => 'required|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            // Get objectType_id from the selected objectSubType
            $objectSubType = ObjectSubType::findOrFail($validated['objectSubType_id']);
            
            // CRITICAL: Add objectType_id to validated data
            $validated['objectType_id'] = $objectSubType->objectType_id;
            
            // Debug to verify it's set
            \Log::info('Creating property with objectType_id: ' . $validated['objectType_id']);
            
            // Set defaults for boolean fields
            $validated['byowner'] = $request->has('byowner') ? 1 : 0;
            $validated['featured'] = $request->has('featured') ? 1 : 0;
            
            // Create property
            $property = Property::create($validated);
            
            // Create property details (only NEW fields)
            $property->details()->create([
                'woonlagen' => $request->woonlagen,
                'woonkamer_count' => $request->woonkamer_count,
                'keuken_count' => $request->keuken_count,
                'toiletten_count' => $request->toiletten_count,
                'parkeergelegenheid_type' => $request->parkeergelegenheid_type,
                'parkeerplaatsen_aantal' => $request->parkeerplaatsen_aantal,
                'airco_algemeen' => $request->has('airco_algemeen') ? 1 : 0,
                'airco_locaties' => $request->airco_locaties,
            ]);
            
            if ($request->has('voorzieningen')) {
                $property->voorzieningen()->attach($request->voorzieningen);
            }
            
            if ($request->has('beveiliging')) {
                $property->beveiliging()->attach($request->beveiliging);
            }
            
            if ($request->has('extra_ruimtes')) {
                $property->extraRuimtes()->attach($request->extra_ruimtes);
            }
            
            if ($request->has('team_members')) {
                $property->teamMembers()->attach($request->team_members);
            }
            DB::commit();
            
            return redirect()->route('admin.properties.show', $property)
                ->with('success', 'Property created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create property: ' . $e->getMessage()]);
        }
    }

    public function edit(Property $property)
    {
        $property->load(['details', 'voorzieningen', 'beveiliging', 'extraRuimtes']);
        
        $districts = District::orderBy('naam')->get();
        $omgevingen = Omgeving::orderBy('naam')->get();
        $objectTypes = ObjectType::orderBy('naam')->get();
        $objectSubTypes = ObjectSubType::with('objectType')->orderBy('naam')->get();
        $currencies = Currency::orderBy('name')->get();
        $titels = Titel::orderBy('naam')->get();
        $voorzieningen = Voorziening::orderBy('naam')->get();
        $beveiligingTypes = BeveiligingType::orderBy('naam')->get();
        $extraRuimteTypes = ExtraRuimteType::orderBy('naam')->get();
        $teamMembers = \App\Models\TeamMember::with('titleType')->orderBy('name')->get();
        
        return view('admin.properties.edit', compact(
            'property',
            'districts',
            'omgevingen',
            'objectTypes',
            'objectSubTypes',
            'currencies',
            'titels',
            'voorzieningen',
            'beveiligingTypes',
            'extraRuimteTypes',
            'teamMembers'
        ));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            // Basic Info
            'naam' => 'required|string|max:50',
            'vraagPrijs' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'currency' => 'required|exists:currencies,id',
            'district_id' => 'required|exists:districten,id',
            'omgeving_id' => 'nullable|exists:omgevingen,id',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'objectSubType_id' => 'required|exists:objectSubTypes,id',
            'status' => 'required|in:1,2,3',
            'titel_id' => 'nullable|exists:titels,id',
            
            // Property Details
            'aantalSlaapkamers' => 'nullable|integer',
            'aantalBadkamers' => 'nullable|integer',
            'woonOppervlakte' => 'nullable|numeric',
            'perceelOppervlakte' => 'nullable|numeric',
            'oppervlakteEenheid' => 'nullable|in:1,2',
            
            // Existing fields in objecten table
            'gemeubileerd' => 'nullable|in:1,2',
            'huurwaarborg' => 'nullable|string|max:60',
            'beschikbaarheid' => 'nullable|string|max:60',
            
            // Description and Media
            'omschrijving' => 'nullable|string',
            'omschrijving_en' => 'nullable|string',
            'youtube' => 'nullable|string|max:255',
            'directions' => 'nullable|string|max:255',
            
            // Flags
            'byowner' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'corporate' => 'required|in:1,2',
            'country' => 'required|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            // Get objectType_id from the selected objectSubType
            $objectSubType = ObjectSubType::findOrFail($validated['objectSubType_id']);
            $validated['objectType_id'] = $objectSubType->objectType_id;
            
            // Set defaults for boolean fields
            $validated['byowner'] = $request->has('byowner') ? 1 : 0;
            $validated['featured'] = $request->has('featured') ? 1 : 0;
            
            // Update property
            $property->update($validated);
            
            // Update or create property details
            $property->details()->updateOrCreate(
                ['property_id' => $property->id],
                [
                    'woonlagen' => $request->woonlagen,
                    'woonkamer_count' => $request->woonkamer_count,
                    'keuken_count' => $request->keuken_count,
                    'toiletten_count' => $request->toiletten_count,
                    'parkeergelegenheid_type' => $request->parkeergelegenheid_type,
                    'parkeerplaatsen_aantal' => $request->parkeerplaatsen_aantal,
                    'airco_algemeen' => $request->has('airco_algemeen') ? 1 : 0,
                    'airco_locaties' => $request->airco_locaties,
                ]
            );
            
            // Sync relationships (replaces all existing with new selection)
            $property->voorzieningen()->sync($request->voorzieningen ?? []);
            $property->beveiliging()->sync($request->beveiliging ?? []);
            $property->extraRuimtes()->sync($request->extra_ruimtes ?? []);
            $property->teamMembers()->sync($request->team_members ?? []);
            
            DB::commit();
            
            return redirect()->route('admin.properties.show', $property)
                ->with('success', 'Property updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update property: ' . $e->getMessage()]);
        }
    }

    public function destroy(Property $property)
    {
        DB::beginTransaction();
        try {
            // Delete featured image
            if ($property->featuredFoto) {
                $imageService = app(ImageService::class);
                $imageService->deleteImageWithVariants(public_path('portal' . $property->featuredFoto));
            }
            
            // Delete all gallery images
            foreach ($property->images as $image) {
                $imageService = app(ImageService::class);
                $imageService->deleteImageWithVariants(public_path('portal' . $image->url));
                $image->delete();
            }
            
            // The cascade delete will handle property_details and pivot tables
            $property->delete();
            
            DB::commit();
            
            return redirect()->route('admin.properties.index')
                ->with('success', 'Object succesvol verwijderd');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Verwijderen mislukt: ' . $e->getMessage()]);
        }
    }

    public function uploadFeatured(Request $request, Property $property, ImageService $imageService)
{
    $request->validate([
        'featuredImage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
    ]);

    if ($request->hasFile('featuredImage')) {
        $oldFeaturedPath = $property->featuredFoto ? public_path('portal' . $property->featuredFoto) : null;
        $file = $request->file('featuredImage');
        $stored = $imageService->storeOptimizedUpload($file, 'portal/uploads/featured');
        $path = '/uploads/featured/' . $stored['filename'];

        try {
            $property->update(['featuredFoto' => $path]);

            if ($oldFeaturedPath) {
                $imageService->deleteImageWithVariants($oldFeaturedPath);
            }
        } catch (\Throwable $e) {
            $imageService->deleteImageWithVariants(public_path('portal' . $path));
            throw $e;
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'url' => asset('portal' . $path),
                'message' => 'Uitgelichte foto succesvol geüpload en geoptimaliseerd'
            ]);
        }
        
        return back()->with('success', 'Uitgelichte foto succesvol geüpload');
    }
    
    return back()->withErrors(['error' => 'Upload mislukt']);
}
    
    public function uploadGallery(Request $request, Property $property, ImageService $imageService)
{
    try {
        if (!$request->hasFile('images') && !$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'message' => 'Geen bestanden geselecteerd'
            ], 400);
        }

        $request->validate([
            'images' => 'nullable|array',
            'images.*' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $files = [];

        if ($request->hasFile('image')) {
            $files[] = $request->file('image');
        }

        if ($request->hasFile('images')) {
            $files = array_merge($files, $request->file('images'));
        }

        $uploadedImages = [];
        $uploadPath = public_path('portal/uploads/objecten');
        
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Get current image count for numbering
        $currentImageCount = $property->images()->count();
        
        foreach ($files as $index => $file) {
            if (!$file->isValid()) {
                continue;
            }

            $stored = $imageService->storeOptimizedUpload($file, 'portal/uploads/objecten');
            $path = '/uploads/objecten/' . $stored['filename'];
            
            // Process image: optimize and generate alt text
            $imageNumber = $currentImageCount + $index + 1;
            $result = [
                'optimized' => true,
                'alt_text' => $imageService->generateAltText($property, $imageNumber),
            ];
            
            // Create image record with alt text and next display order
            $image = $property->images()->create([
                'url' => $path,
                'alt_text' => $result['alt_text'],
                'display_order' => $currentImageCount + $index
            ]);
            
            $uploadedImages[] = [
                'id' => $image->id,
                'url' => asset('portal' . $path)
            ];
        }
        
        if (count($uploadedImages) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Geen bestanden konden worden geüpload'
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'images' => $uploadedImages,
            'message' => count($uploadedImages) . ' foto\'s succesvol geüpload en geoptimaliseerd'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Gallery upload error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Upload mislukt: ' . $e->getMessage()
        ], 500);
    }
}

    public function reorderImages(Request $request, Property $property)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer'
        ]);

        foreach ($request->order as $index => $imageId) {
            PropertyImage::where('id', $imageId)
                ->where('object_id', $property->id)
                ->update(['display_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Volgorde succesvol bijgewerkt'
        ]);
    }

    public function deleteImage(Property $property, PropertyImage $image)
    {
        app(ImageService::class)->deleteImageWithVariants(public_path('portal' . $image->url));
        
        $image->delete();
        
        return back()->with('success', 'Foto succesvol verwijderd');
    }

    public function deleteAllImages(Property $property)
    {
        foreach ($property->images as $image) {
            app(ImageService::class)->deleteImageWithVariants(public_path('portal' . $image->url));
            $image->delete();
        }
        
        return back()->with('success', 'Alle galerij foto\'s succesvol verwijderd');
    }
}
