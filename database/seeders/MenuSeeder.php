<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Topbar Menu
        $topbarMenu = Menu::create([
            'name' => 'Top Bar Menu',
            'location' => 'topbar',
            'is_active' => true,
        ]);

        $topbarItems = [
            ['title' => 'Over Ons', 'title_en' => 'About', 'route_name' => 'about', 'order' => 1],
            ['title' => 'Curaçao', 'title_en' => 'Curaçao', 'url' => 'https://curacao.elevaterealestate.sr/', 'target' => '_blank', 'order' => 2],
            ['title' => 'Luxury Living', 'title_en' => 'Luxury Living', 'url' => '/luxury-living', 'order' => 3],
            ['title' => 'Contact', 'title_en' => 'Contact', 'route_name' => 'contact', 'order' => 4],
        ];

        foreach ($topbarItems as $itemData) {
            MenuItem::create(array_merge($itemData, ['menu_id' => $topbarMenu->id, 'is_active' => true, 'target' => $itemData['target'] ?? '_self']));
        }

        // Create Main Menu
        $mainMenu = Menu::create([
            'name' => 'Main Navigation',
            'location' => 'main',
            'is_active' => true,
        ]);

        // Home
        MenuItem::create([
            'menu_id' => $mainMenu->id,
            'title' => 'Home',
            'title_en' => 'Home',
            'route_name' => 'home',
            'order' => 1,
            'is_active' => true,
        ]);

        // Te Koop with dropdowns
        $teKoop = MenuItem::create([
            'menu_id' => $mainMenu->id,
            'title' => 'Te Koop',
            'title_en' => 'For Sale',
            'route_name' => 'properties.sale',
            'order' => 2,
            'is_active' => true,
        ]);

        $teKoopItems = [
            ['title' => 'Woningen', 'title_en' => 'Houses', 'route_name' => 'properties.sale', 'route_params' => ['object_subtype' => 'woningen']],
            ['title' => 'Percelen', 'title_en' => 'Lots', 'route_name' => 'properties.sale', 'route_params' => ['object_subtype' => 'percelen']],
            ['title' => 'Panden', 'title_en' => 'Buildings', 'route_name' => 'properties.sale', 'route_params' => ['object_subtype' => 'panden']],
        ];

        foreach ($teKoopItems as $index => $item) {
            MenuItem::create([
                'menu_id' => $mainMenu->id,
                'parent_id' => $teKoop->id,
                'title' => $item['title'],
                'title_en' => $item['title_en'],
                'route_name' => $item['route_name'],
                'route_params' => $item['route_params'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Te Huur with dropdowns
        $teHuur = MenuItem::create([
            'menu_id' => $mainMenu->id,
            'title' => 'Te Huur',
            'title_en' => 'For Rent',
            'route_name' => 'properties.rent',
            'order' => 3,
            'is_active' => true,
        ]);

        $teHuurItems = [
            ['title' => 'Woningen', 'title_en' => 'Houses', 'route_name' => 'properties.rent', 'route_params' => ['object_subtype' => 'woningen']],
            ['title' => 'Appartementen', 'title_en' => 'Apartments', 'route_name' => 'properties.rent', 'route_params' => ['object_subtype' => 'appartementen']],
            ['title' => 'Panden', 'title_en' => 'Buildings', 'route_name' => 'properties.rent', 'route_params' => ['object_subtype' => 'panden']],
            ['title' => 'Kantoren', 'title_en' => 'Offices', 'route_name' => 'properties.rent', 'route_params' => ['object_subtype' => 'kantoren']],
            ['title' => 'Bar/Restaurant', 'title_en' => 'Bar/Restaurant', 'route_name' => 'properties.rent', 'route_params' => ['object_subtype' => 'bar-restaurant']],
            ['title' => 'Kantoor/Werkloods', 'title_en' => 'Office/Warehouse', 'route_name' => 'properties.rent', 'route_params' => ['object_subtype' => 'kantoor-werkloods']],
        ];

        foreach ($teHuurItems as $index => $item) {
            MenuItem::create([
                'menu_id' => $mainMenu->id,
                'parent_id' => $teHuur->id,
                'title' => $item['title'],
                'title_en' => $item['title_en'],
                'route_name' => $item['route_name'],
                'route_params' => $item['route_params'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Corporate with dropdowns
        $corporate = MenuItem::create([
            'menu_id' => $mainMenu->id,
            'title' => 'Corporate',
            'title_en' => 'Corporate',
            'route_name' => 'properties.corporate',
            'order' => 4,
            'is_active' => true,
        ]);

        $corporateItems = [
            ['title' => 'Huurwoningen', 'title_en' => 'Rental Houses', 'route_name' => 'properties.corporate', 'route_params' => ['type' => 'huurwoningen']],
            ['title' => 'Huurpanden', 'title_en' => 'Rental Buildings', 'route_name' => 'properties.corporate', 'route_params' => ['type' => 'huurpanden']],
            ['title' => 'Huurkantoren', 'title_en' => 'Rental Offices', 'route_name' => 'properties.corporate', 'route_params' => ['type' => 'huurkantoren']],
            ['title' => 'Koopwoningen', 'title_en' => 'Purchase Houses', 'route_name' => 'properties.corporate', 'route_params' => ['type' => 'koopwoningen']],
            ['title' => 'Kooppanden', 'title_en' => 'Purchase Buildings', 'route_name' => 'properties.corporate', 'route_params' => ['type' => 'kooppanden']],
        ];

        foreach ($corporateItems as $index => $item) {
            MenuItem::create([
                'menu_id' => $mainMenu->id,
                'parent_id' => $corporate->id,
                'title' => $item['title'],
                'title_en' => $item['title_en'],
                'route_name' => $item['route_name'],
                'route_params' => $item['route_params'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // By Owner with dropdowns
        $byOwner = MenuItem::create([
            'menu_id' => $mainMenu->id,
            'title' => 'Door Eigenaar',
            'title_en' => 'By Owner',
            'route_name' => 'properties.by-owner',
            'order' => 5,
            'is_active' => true,
        ]);

        $byOwnerItems = [
            ['title' => 'Te Koop', 'title_en' => 'For Sale', 'route_name' => 'properties.by-owner', 'route_params' => ['type' => 'koop']],
            ['title' => 'Te Huur', 'title_en' => 'For Rent', 'route_name' => 'properties.by-owner', 'route_params' => ['type' => 'huur']],
            ['title' => 'Adverteren Info', 'title_en' => 'Advertise Info', 'route_name' => 'advertise', 'route_params' => []],
        ];

        foreach ($byOwnerItems as $index => $item) {
            MenuItem::create([
                'menu_id' => $mainMenu->id,
                'parent_id' => $byOwner->id,
                'title' => $item['title'],
                'title_en' => $item['title_en'],
                'route_name' => $item['route_name'],
                'route_params' => $item['route_params'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
