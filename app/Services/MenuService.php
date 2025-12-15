<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Get menu by location
     */
    public function getMenuByLocation(string $location)
    {
        return Cache::remember("menu_{$location}", 3600, function () use ($location) {
            return Menu::with(['items.children' => function ($query) {
                $query->where('is_active', true);
            }])
            ->where('location', $location)
            ->where('is_active', true)
            ->first();
        });
    }

    /**
     * Clear menu cache
     */
    public function clearCache()
    {
        Cache::forget('menu_topbar');
        Cache::forget('menu_main');
        Cache::forget('menu_footer');
        Cache::forget('menu_mobile');
    }

    /**
     * Render menu items as HTML
     */
    public function renderMenu(string $location, string $class = '', string $itemClass = '')
    {
        $menu = $this->getMenuByLocation($location);
        
        if (!$menu || !$menu->items->count()) {
            return '';
        }

        $html = '<ul class="' . $class . '">';
        
        foreach ($menu->items as $item) {
            if (!$item->is_active) {
                continue;
            }
            
            $html .= $this->renderMenuItem($item, $itemClass);
        }
        
        $html .= '</ul>';
        
        return $html;
    }

    /**
     * Render a single menu item
     */
    protected function renderMenuItem($item, $itemClass = '')
    {
        $hasChildren = $item->children->where('is_active', true)->count() > 0;
        $activeClass = $this->isActiveItem($item) ? 'active' : '';
        
        $html = '<li class="' . $itemClass . ' ' . $activeClass . '">';
        
        // Build the link
        $target = $item->target ?? '_self';
        $icon = $item->icon ? '<i class="' . $item->icon . '"></i> ' : '';
        
        $html .= '<a href="' . $item->url . '" target="' . $target . '">';
        $html .= $icon . $item->getTranslatedTitle();
        
        if ($hasChildren) {
            $html .= ' <i class="fas fa-chevron-down"></i>';
        }
        
        $html .= '</a>';
        
        // Render children if any
        if ($hasChildren) {
            $html .= '<ul class="dropdown">';
            foreach ($item->children as $child) {
                if ($child->is_active) {
                    $html .= $this->renderMenuItem($child, 'dropdown-item');
                }
            }
            $html .= '</ul>';
        }
        
        $html .= '</li>';
        
        return $html;
    }

    /**
     * Check if menu item is active
     */
    protected function isActiveItem($item)
    {
        if ($item->route_name) {
            return request()->routeIs($item->route_name);
        }
        
        if ($item->url) {
            return request()->url() === $item->url;
        }
        
        return false;
    }
}
