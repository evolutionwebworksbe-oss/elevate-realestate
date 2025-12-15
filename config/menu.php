<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Menu Routes
    |--------------------------------------------------------------------------
    |
    | These routes will appear in the admin menu management dropdown.
    | Add your custom routes here as you create new pages.
    |
    | Format: 'route.name' => 'Display Label',
    |
    */

    'routes' => [
        // Main Pages
        'home' => 'Home',
        'about' => 'About Us / Over Ons',
        'agents' => 'Agents / Makelaars',
        'contact' => 'Contact',
        'advertise' => 'Advertise / Zelf Adverteren',
        
        // Property Listings
        'properties.sale' => 'Properties for Sale / Te Koop',
        'properties.rent' => 'Properties for Rent / Te Huur',
        'properties.corporate' => 'Corporate Properties',
        'properties.by-owner' => 'By Owner / Door Eigenaar',
        'properties.search' => 'Property Search / Zoeken',
        
        // Team
        'team.profile' => 'Team Profile (requires {team} parameter)',
        
        // Add more routes here as needed:
        // 'blog.index' => 'Blog',
        // 'services' => 'Services / Diensten',
    ],
];
