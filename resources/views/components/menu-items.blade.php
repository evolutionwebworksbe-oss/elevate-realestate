@if($menu && $menu->items->count() > 0)
    @foreach($menu->items as $item)
        @if($item->is_active)
            @if($item->children->where('is_active', true)->count() > 0)
                <!-- Dropdown Item -->
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <a href="{{ $item->url }}" 
                       class="text-dark hover:text-primary font-medium transition text-base {{ request()->routeIs($item->route_name) ? 'text-primary' : '' }}">
                        @if($item->icon)
                            <i class="{{ $item->icon }}"></i>
                        @endif
                        {{ $item->getTranslatedTitle() }} <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </a>
                    <div x-show="open" 
                         x-transition
                         class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        @foreach($item->children as $child)
                            @if($child->is_active)
                                <a href="{{ $child->url }}" 
                                   target="{{ $child->target }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-light hover:text-primary transition">
                                    @if($child->icon)
                                        <i class="{{ $child->icon }} mr-2"></i>
                                    @endif
                                    {{ $child->getTranslatedTitle() }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Regular Item -->
                <a href="{{ $item->url }}" 
                   target="{{ $item->target }}"
                   class="text-dark hover:text-primary font-medium transition text-base {{ request()->routeIs($item->route_name) ? 'text-primary' : '' }}">
                    @if($item->icon)
                        <i class="{{ $item->icon }} mr-1"></i>
                    @endif
                    {{ $item->getTranslatedTitle() }}
                </a>
            @endif
        @endif
    @endforeach
@endif
