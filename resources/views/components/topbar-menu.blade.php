@if($menu && $menu->items->count() > 0)
    @foreach($menu->items as $item)
        @if($item->is_active)
            @if($item->children->where('is_active', true)->count() > 0)
                {{-- Item with dropdown --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <a href="{{ $item->url }}" 
                       target="{{ $item->target }}"
                       class="hover:text-accent transition {{ request()->routeIs($item->route_name) ? 'text-accent font-medium' : '' }} inline-flex items-center">
                        @if($item->icon)
                            <i class="{{ $item->icon }} mr-1"></i>
                        @endif
                        {{ $item->getTranslatedTitle() }}
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </a>
                    
                    {{-- Dropdown --}}
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform translate-y-1"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform translate-y-1"
                         class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        @foreach($item->children as $child)
                            @if($child->is_active)
                                <a href="{{ $child->url }}" 
                                   target="{{ $child->target }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-dark hover:text-white transition">
                                    @if($child->icon)
                                        <i class="{{ $child->icon }} mr-2 text-xs"></i>
                                    @endif
                                    {{ $child->getTranslatedTitle() }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Regular item without dropdown --}}
                <a href="{{ $item->url }}" 
                   target="{{ $item->target }}"
                   class="hover:text-accent transition {{ request()->routeIs($item->route_name) ? 'text-accent font-medium' : '' }}">
                    @if($item->icon)
                        <i class="{{ $item->icon }} mr-1"></i>
                    @endif
                    {{ $item->getTranslatedTitle() }}
                </a>
            @endif
        @endif
    @endforeach
@endif
