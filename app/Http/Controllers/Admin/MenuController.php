<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Display a listing of menus
     */
    public function index()
    {
        $menus = Menu::withCount('allItems')->get();
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created menu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|unique:menus,location',
            'is_active' => 'boolean',
        ]);

        Menu::create($validated);

        $this->menuService->clearCache();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    /**
     * Show menu items for editing
     */
    public function edit(Menu $menu)
    {
        $menu->load(['items.children']);
        $availableRoutes = $this->getAvailableRoutes();
        
        return view('admin.menus.edit', compact('menu', 'availableRoutes'));
    }

    /**
     * Update the menu
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|unique:menus,location,' . $menu->id,
            'is_active' => 'boolean',
        ]);

        $menu->update($validated);

        $this->menuService->clearCache();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the menu
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        
        $this->menuService->clearCache();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }

    /**
     * Store a new menu item
     */
    public function storeItem(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'route_params' => 'nullable|json',
            'target' => 'required|in:_self,_blank',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $validated['menu_id'] = $menu->id;
        
        if ($validated['route_params']) {
            $validated['route_params'] = json_decode($validated['route_params'], true);
        }

        MenuItem::create($validated);

        $this->menuService->clearCache();

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item created successfully.');
    }

    /**
     * Update a menu item
     */
    public function updateItem(Request $request, Menu $menu, MenuItem $item)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'route_params' => 'nullable|json',
            'target' => 'required|in:_self,_blank',
            'icon' => 'nullable|string|max:100',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        if ($validated['route_params']) {
            $validated['route_params'] = json_decode($validated['route_params'], true);
        }

        $item->update($validated);

        $this->menuService->clearCache();

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Delete a menu item
     */
    public function destroyItem(Menu $menu, MenuItem $item)
    {
        $item->delete();

        $this->menuService->clearCache();

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Get menu item data for editing
     */
    public function editItem(Menu $menu, MenuItem $item)
    {
        return response()->json($item);
    }

    /**
     * Update menu items order via AJAX
     */
    public function updateOrder(Request $request, Menu $menu)
    {
        $items = $request->input('items', []);

        foreach ($items as $index => $itemData) {
            MenuItem::where('id', $itemData['id'])->update([
                'order' => $index,
                'parent_id' => $itemData['parent_id'] ?? null,
            ]);
        }

        $this->menuService->clearCache();

        return response()->json(['success' => true]);
    }

    /**
     * Get available routes for dropdown
     */
    protected function getAvailableRoutes()
    {
        // Get routes from config file
        $configRoutes = config('menu.routes', []);
        
        // Get all named routes from Laravel to verify they exist
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $availableRoutes = [];
        
        foreach ($configRoutes as $routeName => $label) {
            // Check if route exists in Laravel
            if ($routes->hasNamedRoute($routeName)) {
                $availableRoutes[$routeName] = $label;
            }
        }
        
        // Sort alphabetically by label for better UX
        asort($availableRoutes);
        
        return $availableRoutes;
    }
}
