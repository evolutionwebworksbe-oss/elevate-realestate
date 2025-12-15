<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\ImageService;

class RegenerateImageAltText extends Command
{
    protected $signature = 'images:regenerate-alt-text {--property= : Specific property ID to process}';
    protected $description = 'Regenerate alt text for existing property images';

    public function handle(ImageService $imageService)
    {
        $this->info('Starting alt text regeneration...');

        if ($propertyId = $this->option('property')) {
            $properties = Property::where('id', $propertyId)->get();
            if ($properties->isEmpty()) {
                $this->error("Property with ID {$propertyId} not found.");
                return 1;
            }
        } else {
            $properties = Property::with('images')->get();
        }

        $bar = $this->output->createProgressBar($properties->count());
        $bar->start();

        $totalImages = 0;
        $updatedImages = 0;

        foreach ($properties as $property) {
            foreach ($property->images as $index => $image) {
                $totalImages++;
                
                // Generate alt text
                $altText = $imageService->generateAltText($property, $index + 1);
                
                // Update image
                $image->update(['alt_text' => $altText]);
                $updatedImages++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("Alt text regeneration complete!");
        $this->info("Processed {$properties->count()} properties");
        $this->info("Updated {$updatedImages} / {$totalImages} images");

        return 0;
    }
}
