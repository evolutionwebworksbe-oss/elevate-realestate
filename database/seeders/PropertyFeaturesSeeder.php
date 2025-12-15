<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyFeaturesSeeder extends Seeder
{
    public function run()
    {
        // Voorzieningen
        $voorzieningen = ['Elektriciteit', 'Water', 'WiFi', 'Gas', 'Kabel TV', 'Airconditioning'];
        foreach ($voorzieningen as $v) {
            DB::table('voorzieningen')->insert(['naam' => $v]);
        }
        
        // Beveiliging
        $beveiliging = ['Alarm', 'Camera', 'Bewaking', 'Hekwerk', 'Toegangspoort'];
        foreach ($beveiliging as $b) {
            DB::table('beveiliging_types')->insert(['naam' => $b]);
        }
        
        // Extra Ruimtes
        $ruimtes = ['Terras', 'Balkon', 'Berging', 'Wasruimte', 'Kantoor', 'Conferentieruimte', 'Tuin', 'Zwembad'];
        foreach ($ruimtes as $r) {
            DB::table('extra_ruimte_types')->insert(['naam' => $r]);
        }
    }
}