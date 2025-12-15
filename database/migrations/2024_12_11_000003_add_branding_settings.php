<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add logo and favicon settings
        Setting::create([
            'key' => 'site_logo_menu',
            'value' => 'portal/img/logo.png',
            'type' => 'file',
            'group' => 'branding'
        ]);
        
        Setting::create([
            'key' => 'site_logo_footer',
            'value' => 'portal/img/logo.png',
            'type' => 'file',
            'group' => 'branding'
        ]);
        
        Setting::create([
            'key' => 'site_favicon',
            'value' => 'favicon.ico',
            'type' => 'file',
            'group' => 'branding'
        ]);
        
        Setting::create([
            'key' => 'logo_menu_max_width',
            'value' => '200',
            'type' => 'number',
            'group' => 'branding'
        ]);
        
        Setting::create([
            'key' => 'logo_menu_max_height',
            'value' => '80',
            'type' => 'number',
            'group' => 'branding'
        ]);
        
        Setting::create([
            'key' => 'logo_footer_max_width',
            'value' => '150',
            'type' => 'number',
            'group' => 'branding'
        ]);
        
        Setting::create([
            'key' => 'logo_footer_max_height',
            'value' => '60',
            'type' => 'number',
            'group' => 'branding'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('group', 'branding')->delete();
    }
};
