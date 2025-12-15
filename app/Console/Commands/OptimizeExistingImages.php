<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\ImageService;

class OptimizeExistingImages extends Command
{
    protected $signature = 'images:optimize {--property= : Specific property ID to process}';
    protected $description = 'Optimize existing property images';

    public function handle(ImageService $imageService)
    {
        $this->info('Starting image optimization...');
        $this->warn('This may take a while for large image collections.');

        if ($propertyId = $this->option('property')) {
            $properties = Property::where('id', $propertyId)->get();
            if ($properties->isEmpty()) {
                $this->error("Property with ID {$propertyId} not found.");
                return 1;
            }
        } else {
            if (!$this->confirm('Do you want to optimize ALL property images? This cannot be undone.')) {
                $this->info('Operation cancelled.');
                return 0;
            }
            $properties = Property::with('images')->get();
        }

        $bar = $this->output->createProgressBar($properties->count());
        $bar->start();

        $totalImages = 0;
        $optimizedImages = 0;
        $failedImages = 0;

        foreach ($properties as $property) {
            // Optimize featured image
            if ($property->featuredFoto) {
                $totalImages++;
                $fullPath = public_path('portal' . $property->featuredFoto);
                if (file_exists($fullPath)) {
                    if ($imageService->optimizeImage($fullPath, 1920, 1080, 85)) {
                        $optimizedImages++;
                    } else {
                        $failedImages++;
                    }
                }
            }

            // Optimize gallery images
            foreach ($property->images as $image) {
                $totalImages++;
                $fullPath = public_path('portal' . $image->url);
                if (file_exists($fullPath)) {
                    if ($imageService->optimizeImage($fullPath, 1920, 1080, 85)) {
                        $optimizedImages++;
                    } else {
                        $failedImages++;
                    }
                }
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("Image optimization complete!");
        $this->info("Processed {$properties->count()} properties");
        $this->info("Optimized {$optimizedImages} / {$totalImages} images");
        
        if ($failedImages > 0) {
            $this->warn("{$failedImages} images failed to optimize");
        }

        return 0;
    }
}
