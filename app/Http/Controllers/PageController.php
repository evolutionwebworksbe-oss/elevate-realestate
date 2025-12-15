<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function about()
    {
        // SEO
        SEOMeta::setTitle('Over Ons - Elevate Real Estate');
        SEOMeta::setDescription('Elevate Real Estate is een dynamische organisatie die zich volledig inzet om aan de wensen van haar klanten te voldoen.');
        SEOMeta::setKeywords(['over ons', 'elevate real estate', 'vastgoed suriname', 'makelaar']);
        SEOMeta::setCanonical(url()->current());
        
        OpenGraph::setTitle('Over Ons - Elevate Real Estate');
        OpenGraph::setDescription('Leer ons team en missie kennen');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addImage(asset('portal/img/logo.png'));
        
        $teamMembers = TeamMember::with('titleTypes')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    
        return view('pages.about', compact('teamMembers'));
    }

    public function agents()
    {
        // SEO
        SEOMeta::setTitle(__('messages.our_agents') . ' - Elevate Real Estate');
        SEOMeta::setDescription('Ontmoet ons team van professionele makelaars. Elk met hun eigen expertise en passie voor vastgoed.');
        SEOMeta::setKeywords(['makelaars', 'agents', 'elevate real estate', 'vastgoed suriname', 'team']);
        SEOMeta::setCanonical(url()->current());
        
        OpenGraph::setTitle(__('messages.our_agents') . ' - Elevate Real Estate');
        OpenGraph::setDescription('Ontmoet ons team van professionele makelaars');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addImage(asset('portal/img/logo.png'));
        
        // Get team members with "Real Estate Agent" title OR show_as_agent = true
        $teamMembers = TeamMember::with('titleTypes')
            ->withCount('properties')
            ->where(function($query) {
                $query->whereHas('titleTypes', function($q) {
                    $q->where('name', 'Real Estate Agent')
                      ->orWhere('name', 'Makelaar');
                })
                ->orWhere('show_as_agent', true);
            })
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    
        return view('pages.agents', compact('teamMembers'));
    }

    public function contact()
    {
        // SEO
        SEOMeta::setTitle('Contact - Elevate Real Estate');
        SEOMeta::setDescription('Neem contact op met Elevate Real Estate. Wij staan klaar om u te helpen met al uw vastgoedvragen.');
        SEOMeta::setKeywords(['contact', 'elevate real estate', 'vastgoed suriname']);
        SEOMeta::setCanonical(url()->current());
        
        OpenGraph::setTitle('Contact - Elevate Real Estate');
        OpenGraph::setDescription('Neem contact met ons op');
        OpenGraph::setUrl(url()->current());
        
        return view('pages.contact');
    }

    public function advertise()
    {
        // SEO
        SEOMeta::setTitle('Zelf Adverteren - Elevate Real Estate');
        SEOMeta::setDescription('Adverteer uw vastgoed op ons platform. Bereik duizenden potentiële kopers en huurders.');
        SEOMeta::setKeywords(['adverteren', 'vastgoed verkopen', 'by owner', 'zelf verkopen']);
        SEOMeta::setCanonical(url()->current());
        
        OpenGraph::setTitle('Zelf Adverteren - Elevate Real Estate');
        OpenGraph::setDescription('Adverteer uw vastgoed op ons platform');
        OpenGraph::setUrl(url()->current());
        
        return view('pages.advertise');
    }

    public function teamProfile(TeamMember $team)
    {
        $team->load('titleTypes');
        
        // Get title for SEO - use first title or default
        $titleText = $team->titleTypes->count() > 0 
            ? $team->titleTypes->first()->name 
            : 'makelaar';
        
        // SEO
        SEOMeta::setTitle($team->name . ' - Elevate Real Estate');
        SEOMeta::setDescription('Bekijk het profiel en objecten van ' . $team->name . ', ' . $titleText . ' bij Elevate Real Estate.');
        SEOMeta::setCanonical(url()->current());
        
        OpenGraph::setTitle($team->name . ' - Elevate Real Estate');
        OpenGraph::setDescription('Bekijk objecten van ' . $team->name);
        OpenGraph::setUrl(url()->current());
        if ($team->image) {
            OpenGraph::addImage(asset('portal/' . $team->image));
        }
        
        // Get ALL properties (including sold/rented)
        $allProperties = $team->properties()
            ->with(['district', 'objectSubType', 'currencyRelation', 'objectType'])
            ->orderBy('id', 'desc')
            ->get();
        
        // Get menu structure for categories
        $menuService = app(\App\Services\MenuService::class);
        $mainMenu = $menuService->getMenuByLocation('main');
        
        // Build unified category structure (mixing sale and rent)
        $allCategories = [];
        
        // Build category structure from menu
        if ($mainMenu && $mainMenu->items) {
            foreach ($mainMenu->items as $item) {
                // Te Koop (For Sale)
                if ($item->route_name === 'properties.sale') {
                    foreach ($item->children as $child) {
                        if ($child->is_active && $child->route_params) {
                            $subType = $child->route_params['object_subtype'] ?? null;
                            if ($subType) {
                                $allCategories[] = [
                                    'type' => 'sale',
                                    'type_title' => $item->getTranslatedTitle(),
                                    'key' => 'sale-' . $subType,
                                    'subtype' => $subType,
                                    'title' => $child->getTranslatedTitle(),
                                    'properties' => [],
                                    'order' => \App\Models\CategoryDisplaySetting::getOrder('sale', $subType)
                                ];
                            }
                        }
                    }
                }
                
                // Te Huur (For Rent)
                if ($item->route_name === 'properties.rent') {
                    foreach ($item->children as $child) {
                        if ($child->is_active && $child->route_params) {
                            $subType = $child->route_params['object_subtype'] ?? null;
                            if ($subType) {
                                $allCategories[] = [
                                    'type' => 'rent',
                                    'type_title' => $item->getTranslatedTitle(),
                                    'key' => 'rent-' . $subType,
                                    'subtype' => $subType,
                                    'title' => $child->getTranslatedTitle(),
                                    'properties' => [],
                                    'order' => \App\Models\CategoryDisplaySetting::getOrder('rent', $subType)
                                ];
                            }
                        }
                    }
                }
            }
        }
        
        // Sort all categories by display order
        usort($allCategories, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        
        // Group properties into categories
        foreach ($allProperties as $property) {
            // Get category from objectType relationship
            $category = $property->objectType ? $property->objectType->naam : null;
            $subType = $property->objectSubType ? strtolower($property->objectSubType->naam) : null;
            
            if (!$category || !$subType) {
                continue; // Skip if missing category or subtype
            }
            
            // Check for both 'Koop' and 'Te Koop', 'Huur' and 'Te Huur'
            $isSale = (stripos($category, 'Koop') !== false);
            $isRent = (stripos($category, 'Huur') !== false);
            
            // Find matching category in allCategories
            foreach ($allCategories as &$cat) {
                if ($isSale && $cat['type'] === 'sale' && $cat['subtype'] === $subType) {
                    $cat['properties'][] = $property;
                    break;
                } elseif ($isRent && $cat['type'] === 'rent' && $cat['subtype'] === $subType) {
                    $cat['properties'][] = $property;
                    break;
                }
            }
        }
        
        return view('pages.team-profile', compact('team', 'allCategories'));
    }

    public function newsletterSubscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ]);
        
        \App\Models\Newsletter::create([
            'email' => $validated['email'],
            'subscribed_at' => now()
        ]);
        
        return back()->with('success', 'Bedankt voor uw inschrijving!');
    }   

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'property_id' => 'nullable|integer'
        ]);

        // Store submission in database
        \App\Models\ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'property_id' => $validated['property_id'] ?? null,
            'submitted_at' => now()
        ]);

        // Send email via SendGrid
        $emailService = new \App\Services\EmailService();
        $emailService->sendContactEmail($validated);

        return back()->with('success', 'Bedankt voor uw bericht. We nemen zo spoedig mogelijk contact met u op.');
    }
}