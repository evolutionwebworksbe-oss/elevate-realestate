<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $imageSettings = Setting::where('group', 'images')->get();
        $watermarkSettings = Setting::where('group', 'watermark')->get();
        $brandingSettings = Setting::where('group', 'branding')->get();
        
        // Get current watermark image
        $watermarkPath = Setting::get('watermark_path', 'images/watermark.png');
        $watermarkExists = file_exists(public_path($watermarkPath));
        $watermarkUrl = $watermarkExists ? asset($watermarkPath) : null;
        
        // Get current logo and favicon
        $logoMenuPath = Setting::get('site_logo_menu', 'portal/img/logo.png');
        $logoMenuUrl = file_exists(public_path($logoMenuPath)) ? asset($logoMenuPath) : null;
        
        $logoFooterPath = Setting::get('site_logo_footer', 'portal/img/logo.png');
        $logoFooterUrl = file_exists(public_path($logoFooterPath)) ? asset($logoFooterPath) : null;
        
        $faviconPath = Setting::get('site_favicon', 'favicon.ico');
        $faviconUrl = file_exists(public_path($faviconPath)) ? asset($faviconPath) : null;
        
        return view('admin.settings.index', compact(
            'imageSettings',
            'watermarkSettings',
            'brandingSettings',
            'watermarkUrl',
            'watermarkExists',
            'logoMenuUrl',
            'logoFooterUrl',
            'faviconUrl'
        ));
    }

    public function update(Request $request)
    {
        \Log::info('Settings update request received');
        \Log::info('Files in request:', array_keys($request->allFiles()));
        
        // Check for PHP upload errors
        if ($request->hasFile('logo_footer_file')) {
            $file = $request->file('logo_footer_file');
            if ($file->getError() !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in HTML form',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'PHP extension stopped the upload',
                ];
                $errorCode = $file->getError();
                $errorMsg = $errorMessages[$errorCode] ?? "Unknown error (code {$errorCode})";
                \Log::error('Footer logo upload error:', ['code' => $errorCode, 'message' => $errorMsg]);
                
                return redirect()->route('admin.settings.index')
                    ->withErrors(['logo_footer_file' => "Footer logo upload failed: {$errorMsg}"])
                    ->withInput();
            }
        }
        
        try {
            $validated = $request->validate([
                // Image Optimization
                'image_optimization_enabled' => 'nullable|boolean',
                'image_max_width' => 'required|integer|min:800|max:4000',
                'image_max_height' => 'required|integer|min:600|max:3000',
                'image_quality' => 'required|integer|min:50|max:100',
                
                // Watermark
                'watermark_enabled' => 'nullable|boolean',
                'watermark_size' => 'required|integer|min:10|max:80',
                'watermark_opacity' => 'required|integer|min:10|max:100',
                'watermark_position' => 'required|in:center,top-left,top-right,bottom-left,bottom-right',
                
                // Watermark file
                'watermark_file' => 'nullable|image|mimes:png|max:2048',
                
                // Branding
                'logo_menu_max_width' => 'required|integer|min:50|max:500',
                'logo_menu_max_height' => 'required|integer|min:20|max:200',
                'logo_footer_max_width' => 'required|integer|min:50|max:500',
                'logo_footer_max_height' => 'required|integer|min:20|max:200',
                'logo_menu_file' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:5120',
                'logo_footer_file' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:5120',
                'favicon_file' => 'nullable|file|mimes:png,ico|max:1024'
            ]);
            
            \Log::info('Validation passed', $validated);

            // Update all settings (excluding file uploads)
            foreach ($validated as $key => $value) {
                if (in_array($key, ['watermark_file', 'logo_menu_file', 'logo_footer_file', 'favicon_file'])) {
                    continue; // Handle separately
                }
                
                // Determine type and group
                if (in_array($key, ['image_optimization_enabled', 'watermark_enabled'])) {
                    $type = 'boolean';
                } else {
                    $type = 'number';
                }
                
                if (str_starts_with($key, 'watermark_')) {
                    $group = 'watermark';
                } elseif (str_starts_with($key, 'logo_') || str_starts_with($key, 'favicon_')) {
                    $group = 'branding';
                } else {
                    $group = 'images';
                }
                
                Setting::set($key, $value ?? 0, $type, $group);
                \Log::info("Set {$key} to {$value} in group {$group}");
            }

            // Handle watermark file upload
            if ($request->hasFile('watermark_file')) {
                $file = $request->file('watermark_file');
                
                // Delete old watermark if exists
                $oldPath = Setting::get('watermark_path');
                if ($oldPath && file_exists(public_path($oldPath))) {
                    unlink(public_path($oldPath));
                }
                
                // Save new watermark
                $filename = 'watermark_' . time() . '.png';
                $path = 'images/' . $filename;
                
                // Create directory if doesn't exist
                if (!file_exists(public_path('images'))) {
                    mkdir(public_path('images'), 0755, true);
                }
                
                $file->move(public_path('images'), $filename);
                
                Setting::set('watermark_path', $path, 'file', 'watermark');
                \Log::info("Watermark file uploaded: {$path}");
            }
            
            // Handle logo menu file upload
            if ($request->hasFile('logo_menu_file')) {
                $file = $request->file('logo_menu_file');
                
                // Delete old logo if exists
                $oldPath = Setting::get('site_logo_menu');
                if ($oldPath && $oldPath !== 'portal/img/logo.png' && file_exists(public_path($oldPath))) {
                    unlink(public_path($oldPath));
                }
                
                // Save new logo
                $extension = $file->getClientOriginalExtension();
                $filename = 'logo_menu_' . time() . '.' . $extension;
                $path = 'images/branding/' . $filename;
                
                // Create directory if doesn't exist
                if (!file_exists(public_path('images/branding'))) {
                    mkdir(public_path('images/branding'), 0755, true);
                }
                
                $file->move(public_path('images/branding'), $filename);
                
                // Resize logo
                $imageService = app(\App\Services\ImageService::class);
                $fullPath = public_path('images/branding/' . $filename);
                $maxWidth = (int)($validated['logo_menu_max_width'] ?? 200);
                $maxHeight = (int)($validated['logo_menu_max_height'] ?? 80);
                
                $imageService->optimizeImage($fullPath, $maxWidth, $maxHeight, 90, false);
                
                Setting::set('site_logo_menu', $path, 'file', 'branding');
                \Log::info("Menu logo file uploaded: {$path}");
            }
            
            // Handle logo footer file upload
            if ($request->hasFile('logo_footer_file')) {
                try {
                    \Log::info('Starting footer logo upload');
                    $file = $request->file('logo_footer_file');
                    \Log::info('Footer logo file received', ['name' => $file->getClientOriginalName(), 'size' => $file->getSize()]);
                    
                    // Delete old logo if exists
                    $oldPath = Setting::get('site_logo_footer');
                    if ($oldPath && $oldPath !== 'portal/img/logo.png' && file_exists(public_path($oldPath))) {
                        unlink(public_path($oldPath));
                        \Log::info('Deleted old footer logo', ['path' => $oldPath]);
                    }
                    
                    // Save new logo
                    $extension = $file->getClientOriginalExtension();
                    $filename = 'logo_footer_' . time() . '.' . $extension;
                    $path = 'images/branding/' . $filename;
                    
                    // Create directory if doesn't exist
                    if (!file_exists(public_path('images/branding'))) {
                        mkdir(public_path('images/branding'), 0755, true);
                        \Log::info('Created branding directory');
                    }
                    
                    $file->move(public_path('images/branding'), $filename);
                    \Log::info('Footer logo moved to branding directory', ['filename' => $filename]);
                    
                    // Resize logo
                    $imageService = app(\App\Services\ImageService::class);
                    $fullPath = public_path('images/branding/' . $filename);
                    $maxWidth = (int)($validated['logo_footer_max_width'] ?? 150);
                    $maxHeight = (int)($validated['logo_footer_max_height'] ?? 60);
                    \Log::info('Resizing footer logo', ['width' => $maxWidth, 'height' => $maxHeight]);
                    
                    $imageService->optimizeImage($fullPath, $maxWidth, $maxHeight, 90, false);
                    \Log::info('Footer logo optimized');
                    
                    Setting::set('site_logo_footer', $path, 'file', 'branding');
                    \Log::info("Footer logo file uploaded successfully: {$path}");
                } catch (\Exception $e) {
                    \Log::error('Footer logo upload failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e; // Re-throw to be caught by outer try-catch
                }
            }
            
            // Handle favicon file upload
            if ($request->hasFile('favicon_file')) {
                $file = $request->file('favicon_file');
                
                // Delete old favicon if exists
                $oldPath = Setting::get('site_favicon');
                if ($oldPath && $oldPath !== 'favicon.ico' && file_exists(public_path($oldPath))) {
                    unlink(public_path($oldPath));
                }
                
                // Save new favicon
                $extension = $file->getClientOriginalExtension();
                $filename = 'favicon_' . time() . '.' . $extension;
                $path = 'images/branding/' . $filename;
                
                // Create directory if doesn't exist
                if (!file_exists(public_path('images/branding'))) {
                    mkdir(public_path('images/branding'), 0755, true);
                }
                
                $file->move(public_path('images/branding'), $filename);
                
                // Resize favicon to 32x32
                $imageService = app(\App\Services\ImageService::class);
                $fullPath = public_path('images/branding/' . $filename);
                $imageService->optimizeImage($fullPath, 32, 32, 90, false);
                
                Setting::set('site_favicon', $path, 'file', 'branding');
                \Log::info("Favicon file uploaded: {$path}");
            }

            // Clear settings cache
            Setting::clearCache();

            return redirect()->route('admin.settings.index')
                ->with('success', 'Instellingen succesvol bijgewerkt');
                
        } catch (\Exception $e) {
            \Log::error('Settings update failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->route('admin.settings.index')
                ->withErrors(['error' => 'Fout bij opslaan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function deleteWatermark()
    {
        $watermarkPath = Setting::get('watermark_path');
        
        if ($watermarkPath && file_exists(public_path($watermarkPath))) {
            unlink(public_path($watermarkPath));
        }
        
        Setting::set('watermark_path', '', 'file', 'watermark');
        Setting::set('watermark_enabled', 0, 'boolean', 'watermark');
        Setting::clearCache();
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Watermerk succesvol verwijderd');
    }

    public function categoryOrder()
    {
        // Get all categories from menu
        $menuService = app(\App\Services\MenuService::class);
        $mainMenu = $menuService->getMenuByLocation('main');
        
        $saleCategories = [];
        $rentCategories = [];
        
        if ($mainMenu && $mainMenu->items) {
            foreach ($mainMenu->items as $item) {
                if ($item->route_name === 'properties.sale') {
                    foreach ($item->children as $child) {
                        if ($child->is_active && $child->route_params) {
                            $subType = $child->route_params['object_subtype'] ?? null;
                            if ($subType) {
                                $saleCategories[$subType] = [
                                    'title' => $child->getTranslatedTitle(),
                                    'order' => \App\Models\CategoryDisplaySetting::getOrder('sale', $subType)
                                ];
                            }
                        }
                    }
                }
                
                if ($item->route_name === 'properties.rent') {
                    foreach ($item->children as $child) {
                        if ($child->is_active && $child->route_params) {
                            $subType = $child->route_params['object_subtype'] ?? null;
                            if ($subType) {
                                $rentCategories[$subType] = [
                                    'title' => $child->getTranslatedTitle(),
                                    'order' => \App\Models\CategoryDisplaySetting::getOrder('rent', $subType)
                                ];
                            }
                        }
                    }
                }
            }
        }
        
        // Sort by current order
        uasort($saleCategories, fn($a, $b) => $a['order'] <=> $b['order']);
        uasort($rentCategories, fn($a, $b) => $a['order'] <=> $b['order']);
        
        return view('admin.settings.category-order', compact('saleCategories', 'rentCategories'));
    }
    
    public function updateCategoryOrder(Request $request)
    {
        $validated = $request->validate([
            'sale_order' => 'required|array',
            'sale_order.*' => 'integer',
            'rent_order' => 'required|array',
            'rent_order.*' => 'integer',
        ]);
        
        // Update sale categories
        foreach ($validated['sale_order'] as $subtype => $order) {
            \App\Models\CategoryDisplaySetting::setOrder('sale', $subtype, $order);
        }
        
        // Update rent categories
        foreach ($validated['rent_order'] as $subtype => $order) {
            \App\Models\CategoryDisplaySetting::setOrder('rent', $subtype, $order);
        }
        
        return redirect()->route('admin.settings.category-order')
            ->with('success', 'Categorie volgorde succesvol bijgewerkt');
    }

    public function testWatermark(Request $request)
    {
        $request->validate([
            'test_image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($request->hasFile('test_image')) {
            $file = $request->file('test_image');
            $filename = 'test_watermark_' . time() . '.' . $file->getClientOriginalExtension();
            $path = 'images/tests/' . $filename;
            
            // Create directory
            if (!file_exists(public_path('images/tests'))) {
                mkdir(public_path('images/tests'), 0755, true);
            }
            
            $file->move(public_path('images/tests'), $filename);
            
            // Apply watermark using ImageService
            $imageService = app(\App\Services\ImageService::class);
            $fullPath = public_path('images/tests/' . $filename);
            
            // Always apply watermark for test (force true)
            $maxWidth = (int)Setting::get('image_max_width', 1920);
            $maxHeight = (int)Setting::get('image_max_height', 1080);
            $quality = (int)Setting::get('image_quality', 85);
            
            \Log::info('Testing watermark with settings:', [
                'watermark_enabled' => Setting::get('watermark_enabled'),
                'watermark_path' => Setting::get('watermark_path'),
                'watermark_size' => Setting::get('watermark_size'),
                'watermark_opacity' => Setting::get('watermark_opacity'),
                'watermark_position' => Setting::get('watermark_position')
            ]);
            
            $result = $imageService->optimizeImage($fullPath, $maxWidth, $maxHeight, $quality, true);
            
            return response()->json([
                'success' => $result,
                'url' => asset('images/tests/' . $filename),
                'message' => $result ? 'Test afbeelding met watermerk aangemaakt' : 'Afbeelding geüpload maar watermerk kon niet worden toegepast'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Upload mislukt'
        ], 400);
    }
}
