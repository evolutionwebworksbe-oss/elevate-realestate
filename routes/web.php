<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\ObjectSubTypeController;
use App\Http\Controllers\Admin\ObjectTypeController;
use App\Http\Controllers\Admin\OmgevingController;
use App\Http\Controllers\Admin\PandtypeController;
use App\Http\Controllers\Admin\TeamTitleTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;
use App\Models\Property;
use App\Models\TeamMember;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('properties', PropertyController::class)->names('properties');
    Route::post('properties/{property}/upload-featured', [PropertyController::class, 'uploadFeatured'])->name('properties.upload-featured');
    Route::post('properties/translate', [PropertyController::class, 'translate'])->name('properties.translate');
    Route::post('properties/{property}/upload-gallery', [PropertyController::class, 'uploadGallery'])->name('properties.upload-gallery');
    Route::post('properties/{property}/reorder-images', [PropertyController::class, 'reorderImages'])->name('properties.reorder-images');
    Route::delete('properties/{property}/images/{image}', [PropertyController::class, 'deleteImage'])->name('properties.delete-image');
    Route::delete('properties/{property}/images', [PropertyController::class, 'deleteAllImages'])->name('properties.delete-all-images');
    Route::resource('team', TeamController::class)->names('team');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('sliders', SliderController::class)->names('sliders');
    Route::get('newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::delete('newsletters/{newsletter}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');
    Route::get('newsletters/export', [\App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletters.export');
    
    // Menu Management Routes
    Route::resource('menus', MenuController::class)->names('menus');
    Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
    Route::put('menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
    Route::delete('menus/{menu}/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
    Route::get('menus/{menu}/items/{item}/edit', [MenuController::class, 'editItem'])->name('menus.items.edit');
    Route::post('menus/{menu}/update-order', [MenuController::class, 'updateOrder'])->name('menus.update-order');
    
    // Settings Routes
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/watermark', [\App\Http\Controllers\Admin\SettingsController::class, 'deleteWatermark'])->name('settings.watermark.delete');
    Route::post('/settings/watermark/test', [\App\Http\Controllers\Admin\SettingsController::class, 'testWatermark'])->name('settings.watermark.test');
    
    // Category Order Routes
    Route::get('/settings/category-order', [\App\Http\Controllers\Admin\SettingsController::class, 'categoryOrder'])->name('settings.category-order');
    Route::post('/settings/category-order', [\App\Http\Controllers\Admin\SettingsController::class, 'updateCategoryOrder'])->name('settings.category-order.update');
});

Route::prefix('settings')->name('admin.settings.')->group(function () {
    Route::resource('currencies', CurrencyController::class);
    Route::resource('districts', DistrictController::class);
    Route::resource('omgevingen', OmgevingController::class);
    Route::resource('object-types', ObjectTypeController::class);
    Route::resource('object-subtypes', ObjectSubTypeController::class);
    Route::resource('team-titletypes', TeamTitleTypeController::class);
    Route::resource('pand-types', PandTypeController::class);
    Route::get('newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::delete('newsletters/{newsletter}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');
    Route::get('newsletters/export', [\App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletters.export');
});

Route::get('/api/omgevingen', function(Request $request) {
    if (!$request->has('district_id') || empty($request->district_id)) {
        return response()->json([]);
    }
    
    $omgevingen = \App\Models\Omgeving::where('district_id', $request->district_id)
        ->orderBy('naam')
        ->get(['id', 'naam']);
    
    return response()->json($omgevingen);
});

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['nl', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Public Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/te-koop', [App\Http\Controllers\PropertyController::class, 'sale'])->name('properties.sale');
Route::get('/te-huur', [App\Http\Controllers\PropertyController::class, 'rent'])->name('properties.rent');
Route::get('/corporate', [App\Http\Controllers\PropertyController::class, 'corporate'])->name('properties.corporate');
Route::get('/door-eigenaar', [App\Http\Controllers\PropertyController::class, 'byOwner'])->name('properties.by-owner');
Route::get('/object', function (Request $request) {
    $naam = $request->query('naam');
    
    if ($naam) {
        return redirect("/object/{$naam}", 301);
    }
    
    return redirect('/objecten', 301);
});
Route::get('/object/{property:slug}', [App\Http\Controllers\PropertyController::class, 'show'])->name('properties.show');
Route::get('/zoeken', [App\Http\Controllers\PropertyController::class, 'search'])->name('properties.search');

// Live Search API
Route::get('/api/properties/live-search', [App\Http\Controllers\PropertyController::class, 'liveSearch'])->name('properties.live-search');

Route::get('/over-ons', [App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/makelaars', [App\Http\Controllers\PageController::class, 'agents'])->name('agents');
Route::get('/contact', [App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/team/{team}', [App\Http\Controllers\PageController::class, 'teamProfile'])->name('team.profile');
Route::get('/zelf-adverteren', [App\Http\Controllers\PageController::class, 'advertise'])->name('advertise');
Route::post('/newsletter/subscribe', [App\Http\Controllers\PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

require __DIR__.'/auth.php';
