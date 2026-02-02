{{-- Mobile menu uses same items as desktop main menu + topbar menu with expandable dropdowns --}}
<div class="px-4">

{{-- Main Navigation Items --}}
@if($mainMenu && $mainMenu->items->count() > 0)
    @foreach($mainMenu->items as $item)
        @if($item->is_active)
            @if($item->children->where('is_active', true)->count() > 0)
                {{-- Item with dropdown --}}
                <div x-data="{ open: false }" class="border-b border-gray-100">
                    <div class="flex items-center justify-between py-3">
                        <a href="{{ $item->url }}" 
                           target="{{ $item->target }}"
                           class="flex-1 text-dark hover:text-primary font-medium {{ request()->routeIs($item->route_name) ? 'text-primary' : '' }}">
                            @if($item->icon)
                                <i class="{{ $item->icon }} mr-2"></i>
                            @endif
                            {{ $item->getTranslatedTitle() }}
                        </a>
                        <button @click="open = !open" class="p-2 text-gray-500 hover:text-primary transition">
                            <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-sm"></i>
                        </button>
                    </div>
                    
                    {{-- Dropdown items --}}
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="pl-6 pb-2 space-y-1 bg-gray-50">
                        @foreach($item->children as $child)
                            @if($child->is_active)
                                <a href="{{ $child->url }}" 
                                   target="{{ $child->target }}"
                                   class="block py-2 px-3 text-sm text-gray-700 hover:text-primary hover:bg-white rounded transition {{ request()->url() === $child->url ? 'text-primary bg-white font-medium' : '' }}">
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
                   class="block py-3 text-dark hover:text-primary font-medium border-b border-gray-100 {{ request()->routeIs($item->route_name) ? 'text-primary' : '' }}">
                    @if($item->icon)
                        <i class="{{ $item->icon }} mr-2"></i>
                    @endif
                    {{ $item->getTranslatedTitle() }}
                </a>
            @endif
        @endif
    @endforeach
@endif
</div>
</div>

{{-- Divider between main and topbar menus --}}
@if($mainMenu && $mainMenu->items->count() > 0 && $topbarMenu && $topbarMenu->items->count() > 0)
    <div class="my-2 border-t-2 border-gray-200"></div>
@endif

{{-- Topbar Menu Items --}}
<div class="px-4">
@if($topbarMenu && $topbarMenu->items->count() > 0)
    @foreach($topbarMenu->items as $item)
        @if($item->is_active)
            @if($item->children->where('is_active', true)->count() > 0)
                {{-- Item with dropdown --}}
                <div x-data="{ open: false }" class="border-b border-gray-100">
                    <div class="flex items-center justify-between py-3">
                        <a href="{{ $item->url }}" 
                           target="{{ $item->target }}"
                           class="flex-1 text-dark hover:text-primary font-medium {{ request()->routeIs($item->route_name) ? 'text-primary' : '' }}">
                            @if($item->icon)
                                <i class="{{ $item->icon }} mr-2"></i>
                            @endif
                            {{ $item->getTranslatedTitle() }}
                        </a>
                        <button @click="open = !open" class="p-2 text-gray-500 hover:text-primary transition">
                            <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-sm"></i>
                        </button>
                    </div>
                    
                    {{-- Dropdown items --}}
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="pl-6 pb-2 space-y-1 bg-gray-50">
                        @foreach($item->children as $child)
                            @if($child->is_active)
                                <a href="{{ $child->url }}" 
                                   target="{{ $child->target }}"
                                   class="block py-2 px-3 text-sm text-gray-700 hover:text-primary hover:bg-white rounded transition {{ request()->url() === $child->url ? 'text-primary bg-white font-medium' : '' }}">
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
                   class="block py-3 text-dark hover:text-primary font-medium border-b border-gray-100 {{ request()->routeIs($item->route_name) ? 'text-primary' : '' }}">
                    @if($item->icon)
                        <i class="{{ $item->icon }} mr-2"></i>
                    @endif
                    {{ $item->getTranslatedTitle() }}
                </a>
            @endif
        @endif
    @endforeach
@endif
