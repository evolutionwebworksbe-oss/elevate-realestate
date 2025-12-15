<?php

namespace App\Services;

use App\Models\Property;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected $imageManager;
    
    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }
    
    /**
     * Optimize and compress an image
     * 
     * @param string $sourcePath Full path to source image
     * @param int $maxWidth Maximum width in pixels
     * @param int $maxHeight Maximum height in pixels
     * @param int $quality Quality (0-100)
     * @param bool $addWatermark Whether to add watermark
     * @return bool Success
     */
    public function optimizeImage($sourcePath, $maxWidth = 1920, $maxHeight = 1080, $quality = 85, $addWatermark = false)
    {
        try {
            // Read the image
            $image = $this->imageManager->read($sourcePath);
            
            // Get current dimensions
            $width = $image->width();
            $height = $image->height();
            
            // Only resize if image is larger than max dimensions
            if ($width > $maxWidth || $height > $maxHeight) {
                // Calculate aspect ratio
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);
                
                // Resize image
                $image->scale($newWidth, $newHeight);
            }
            
            // Add watermark if requested
            if ($addWatermark) {
                $this->addWatermark($image);
            }
            
            // Save optimized image
            $image->save($sourcePath, $quality);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add watermark to an image
     * 
     * @param mixed $image Intervention Image instance
     * @return void
     */
    protected function addWatermark($image)
    {
        // Get watermark path from database settings
        $watermarkRelativePath = \App\Models\Setting::get('watermark_path', 'images/watermark.png');
        $watermarkPath = public_path($watermarkRelativePath);
        
        \Log::info('Attempting to add watermark', [
            'relative_path' => $watermarkRelativePath,
            'full_path' => $watermarkPath,
            'file_exists' => file_exists($watermarkPath)
        ]);
        
        // Check if watermark file exists
        if (!file_exists($watermarkPath)) {
            \Log::warning('Watermark file not found at: ' . $watermarkPath);
            return;
        }
        
        try {
            // Read watermark
            $watermark = $this->imageManager->read($watermarkPath);
            
            // Get image dimensions
            $imageWidth = $image->width();
            $imageHeight = $image->height();
            
            // Calculate watermark size based on database settings
            $sizePercentage = (int)\App\Models\Setting::get('watermark_size', 30) / 100;
            $watermarkWidth = (int)($imageWidth * $sizePercentage);
            
            // Resize watermark maintaining aspect ratio
            $watermark->scale($watermarkWidth);
            
            // Get opacity from database settings
            $opacity = (int)\App\Models\Setting::get('watermark_opacity', 50);
            
            // Get position from database settings
            $position = \App\Models\Setting::get('watermark_position', 'center');
            $offsetX = 0;
            $offsetY = 0;
            
            \Log::info('Applying watermark with settings', [
                'size_percentage' => $sizePercentage,
                'watermark_width' => $watermarkWidth,
                'opacity' => $opacity,
                'position' => $position,
                'image_dimensions' => $imageWidth . 'x' . $imageHeight
            ]);
            
            // Apply watermark with configured settings
            $image->place($watermark, $position, $offsetX, $offsetY, $opacity);
            
        } catch (\Exception $e) {
            \Log::error('Watermark failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate SEO-friendly alt text for property image
     * 
     * @param Property $property
     * @param int $imageIndex Image number (1-based)
     * @return string Alt text
     */
    public function generateAltText(Property $property, $imageIndex = null)
    {
        $locale = app()->getLocale();
        
        // Get property name
        $propertyName = $locale == 'en' && $property->naam_en 
            ? $property->naam_en 
            : $property->naam;
        
        // Get object type
        $objectType = $property->objectType->naam ?? '';
        if ($locale == 'en') {
            $objectTypeTranslations = [
                'Te Koop' => 'For Sale',
                'Te Huur' => 'For Rent',
            ];
            $objectType = $objectTypeTranslations[$objectType] ?? $objectType;
        }
        
        // Get object subtype
        $objectSubType = $property->objectSubType->naam ?? '';
        if ($locale == 'en') {
            $objectSubTypeTranslations = [
                'Woningen' => 'House',
                'Percelen' => 'Lot',
                'Panden' => 'Building',
                'Appartementen' => 'Apartment',
                'Kantoren' => 'Office',
                'Bar/Restaurant' => 'Bar/Restaurant',
                'Kantoor met werkloods' => 'Office with Warehouse',
            ];
            $objectSubType = $objectSubTypeTranslations[$objectSubType] ?? $objectSubType;
        }
        
        // Get district
        $district = $property->district->naam ?? '';
        
        // Build alt text
        $parts = array_filter([
            $propertyName,
            $objectSubType,
            $objectType,
            $district ? "in $district" : null
        ]);
        
        $altText = implode(' - ', $parts);
        
        // Add image number if provided
        if ($imageIndex !== null) {
            $altText .= " - " . ($locale == 'en' ? "Image" : "Foto") . " $imageIndex";
        }
        
        return $altText;
    }
    
    /**
     * Process uploaded image: optimize and generate alt text
     * 
     * @param string $imagePath Path to image (relative to public)
     * @param Property $property
     * @param int|null $imageIndex
     * @param bool|null $addWatermark Whether to add watermark (null = use database settings)
     * @return array ['optimized' => bool, 'alt_text' => string]
     */
    public function processPropertyImage($imagePath, Property $property, $imageIndex = null, $addWatermark = null)
    {
        // Full path to image
        $fullPath = public_path($imagePath);
        
        // Use database setting if not specified
        if ($addWatermark === null) {
            $addWatermark = filter_var(\App\Models\Setting::get('watermark_enabled', false), FILTER_VALIDATE_BOOLEAN);
        }
        
        // Get optimization settings from database
        $maxWidth = (int)\App\Models\Setting::get('image_max_width', 1920);
        $maxHeight = (int)\App\Models\Setting::get('image_max_height', 1080);
        $quality = (int)\App\Models\Setting::get('image_quality', 85);
        
        // Optimize image (with optional watermark)
        $optimized = $this->optimizeImage($fullPath, $maxWidth, $maxHeight, $quality, $addWatermark);
        
        // Generate alt text
        $altText = $this->generateAltText($property, $imageIndex);
        
        return [
            'optimized' => $optimized,
            'alt_text' => $altText
        ];
    }
}
