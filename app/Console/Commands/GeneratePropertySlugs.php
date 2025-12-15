<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GeneratePropertySlugs extends Command
{
    protected $signature = 'properties:generate-slugs';
    protected $description = 'Generate slugs for all existing properties';

    public function handle()
    {
        $properties = Property::whereNull('slug')->orWhere('slug', '')->get();
        
        $this->info("Found {$properties->count()} properties without slugs");
        
        $bar = $this->output->createProgressBar($properties->count());
        $bar->start();
        
        foreach ($properties as $property) {
            $slug = Str::slug($property->naam);
            
            // Check if slug exists, add number if needed
            $originalSlug = $slug;
            $count = 1;
            
            while (Property::where('slug', $slug)->where('id', '!=', $property->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            $property->slug = $slug;
            $property->save();
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Slugs generated successfully!');
        
        return 0;
    }
}