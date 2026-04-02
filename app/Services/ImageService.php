<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    public const RESPONSIVE_WIDTHS = [480, 768, 1280, 1920];

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
    public function optimizeImage($sourcePath, $maxWidth = 2560, $maxHeight = 1600, $quality = 82, $addWatermark = false)
    {
        try {
            // Read the image
            $image = $this->imageManager->read($sourcePath);

            // Respect EXIF orientation before resizing
            $image->orient();

            $originalWidth = $image->width();
            $originalHeight = $image->height();

            $image->scaleDown($maxWidth, $maxHeight);

            if ($image->width() < $originalWidth || $image->height() < $originalHeight) {
                $image->sharpen(6);
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
     * Store a delivery-optimized image for frontend usage.
     *
     * New property/project uploads are normalized to WebP with sensible
     * display dimensions so a single uploaded photo is lightweight enough
     * for desktop and mobile while still looking sharp.
     */
    public function storeOptimizedUpload(
        UploadedFile $file,
        string $relativeDirectory,
        ?bool $addWatermark = null,
        ?int $maxWidth = null,
        ?int $maxHeight = null,
        ?int $quality = null
    ): array {
        $relativeDirectory = trim($relativeDirectory, '/');
        $fullDirectory = public_path($relativeDirectory);

        if (!file_exists($fullDirectory)) {
            mkdir($fullDirectory, 0755, true);
        }

        $maxWidth = $maxWidth ?? (int) \App\Models\Setting::get('image_max_width', 2560);
        $maxHeight = $maxHeight ?? (int) \App\Models\Setting::get('image_max_height', 1600);
        $quality = $quality ?? (int) \App\Models\Setting::get('image_quality', 82);

        if ($addWatermark === null) {
            $addWatermark = filter_var(\App\Models\Setting::get('watermark_enabled', false), FILTER_VALIDATE_BOOLEAN);
        }

        $image = $this->imageManager->read($file->getRealPath());
        $image->orient();

        $originalWidth = $image->width();
        $originalHeight = $image->height();

        $image->scaleDown($maxWidth, $maxHeight);

        if ($image->width() < $originalWidth || $image->height() < $originalHeight) {
            $image->sharpen(6);
        }

        if ($addWatermark) {
            $this->addWatermark($image);
        }

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBaseName = Str::slug($baseName) ?: 'image';
        $filename = time() . '_' . uniqid() . '_' . $safeBaseName . '.webp';
        $fullPath = $fullDirectory . DIRECTORY_SEPARATOR . $filename;

        $image->toWebp($quality)->save($fullPath);
        $this->generateResponsiveVariants($fullPath, $image->width(), $quality);

        return [
            'filename' => $filename,
            'full_path' => $fullPath,
            'relative_path' => $relativeDirectory . '/' . $filename,
            'width' => $image->width(),
            'height' => $image->height(),
            'format' => 'webp',
        ];
    }

    public static function variantPath(string $relativePath, int $width): string
    {
        $info = pathinfo($relativePath);
        $directory = $info['dirname'] ?? '';
        $filename = $info['filename'] ?? '';
        $variant = $filename . '__w' . $width . '.webp';

        return ($directory && $directory !== '.')
            ? $directory . '/' . $variant
            : $variant;
    }

    public static function expectedResponsiveWidths(?int $sourceWidth = null): array
    {
        if (!$sourceWidth) {
            return self::RESPONSIVE_WIDTHS;
        }

        return array_values(array_filter(
            self::RESPONSIVE_WIDTHS,
            fn (int $width) => $width < $sourceWidth
        ));
    }

    public static function existingResponsiveVariantPaths(string $relativePath): array
    {
        static $cache = [];

        $relativePath = ltrim($relativePath, '/');

        if (array_key_exists($relativePath, $cache)) {
            return $cache[$relativePath];
        }

        $paths = [];

        foreach (self::RESPONSIVE_WIDTHS as $width) {
            $variantRelativePath = self::variantPath($relativePath, $width);

            if (file_exists(public_path($variantRelativePath))) {
                $paths[$width] = $variantRelativePath;
            }
        }

        return $cache[$relativePath] = $paths;
    }

    public function deleteImageWithVariants(string $fullPath): void
    {
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }

        foreach (self::RESPONSIVE_WIDTHS as $width) {
            $variantPath = self::variantPath($fullPath, $width);
            if (file_exists($variantPath)) {
                @unlink($variantPath);
            }
        }
    }

    private function generateResponsiveVariants(string $fullPath, int $originalWidth, int $quality): void
    {
        foreach (self::RESPONSIVE_WIDTHS as $width) {
            if ($width >= $originalWidth) {
                continue;
            }

            $variantPath = self::variantPath($fullPath, $width);
            $variant = $this->imageManager->read($fullPath);
            $variant->scaleDown($width, null);
            $variant->sharpen(4);
            $variant->toWebp($quality)->save($variantPath);
        }
    }

    public function backfillResponsiveVariants(string $fullPath, ?int $quality = null): bool
    {
        try {
            if (!file_exists($fullPath)) {
                return false;
            }

            $quality = $quality ?? (int) \App\Models\Setting::get('image_quality', 82);
            $image = $this->imageManager->read($fullPath);
            $image->orient();

            $this->generateResponsiveVariants($fullPath, $image->width(), $quality);

            return true;
        } catch (\Throwable $e) {
            \Log::error('Responsive variant backfill failed: ' . $e->getMessage(), [
                'path' => $fullPath,
            ]);

            return false;
        }
    }

    public function hasCompleteResponsiveSet(string $fullPath): bool
    {
        if (!file_exists($fullPath)) {
            return false;
        }

        [$sourceWidth] = @getimagesize($fullPath) ?: [null, null];
        if (!$sourceWidth) {
            return false;
        }

        foreach (self::expectedResponsiveWidths($sourceWidth) as $width) {
            if (!file_exists(self::variantPath($fullPath, $width))) {
                return false;
            }
        }

        return true;
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
        $maxWidth = (int)\App\Models\Setting::get('image_max_width', 2560);
        $maxHeight = (int)\App\Models\Setting::get('image_max_height', 1600);
        $quality = (int)\App\Models\Setting::get('image_quality', 82);
        
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
