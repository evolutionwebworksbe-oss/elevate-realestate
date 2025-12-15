<x-app-layout>
    <x-slot name="title">{{ $team->name }} - Elevate Real Estate</x-slot>

    <div class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="lg:flex lg:gap-8">
                
                <!-- Left Sidebar - Agent Info (Fixed on Desktop) -->
                <div class="lg:w-1/3 mb-8 lg:mb-0">
                    <div class="lg:sticky lg:top-24">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Agent Photo -->
                            <div class="relative h-80 lg:h-96 bg-gray-200">
                                @if($team->image)
                                    <img src="{{ asset('portal/' . $team->image) }}" 
                                         alt="{{ $team->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary-dark">
                                        <i class="fas fa-user text-white text-8xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Agent Details -->
                            <div class="p-6">
                                <h1 class="text-3xl font-bold text-dark mb-3">{{ $team->name }}</h1>
                                
                                @if($team->titleTypes && $team->titleTypes->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($team->titleTypes as $title)
                                            <span class="px-3 py-1 bg-primary/10 text-primary text-sm rounded-full">
                                                {{ app()->getLocale() == 'en' && $title->name_en ? $title->name_en : $title->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($team->bio ?? $team->description)
                                    <p class="text-gray-600 mb-6 leading-relaxed">
                                        {{ app()->getLocale() == 'en' && $team->bio_en ? $team->bio_en : ($team->bio ?? $team->description) }}
                                    </p>
                                @endif

                                <!-- Contact Information -->
                                <div class="space-y-3 border-t pt-6">
                                    @if($team->phone)
                                        <a href="tel:{{ $team->phone }}" 
                                           class="flex items-center gap-3 text-dark hover:text-primary transition group">
                                            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary group-hover:text-white transition">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <span class="font-medium">{{ $team->phone }}</span>
                                        </a>
                                    @endif

                                    @if($team->whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $team->whatsapp) }}" 
                                           target="_blank"
                                           class="flex items-center gap-3 text-dark hover:text-green-600 transition group">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
                                                <i class="fab fa-whatsapp"></i>
                                            </div>
                                            <span class="font-medium">{{ $team->whatsapp }}</span>
                                        </a>
                                    @endif

                                    @if($team->email)
                                        <a href="mailto:{{ $team->email }}" 
                                           class="flex items-center gap-3 text-dark hover:text-primary transition group">
                                            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary group-hover:text-white transition">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <span class="font-medium text-sm">{{ $team->email }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Properties by Category -->
                <div class="lg:w-2/3">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-dark">{{ __('messages.properties_by') }} {{ $team->name }}</h2>
                        <p class="text-gray-600">{{ __('messages.browse_all_properties_agent') }}</p>
                    </div>

                    <!-- Quick Filter Navigation -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 sticky top-24 z-40" x-data="{ filterOpen: false }">
                        <!-- Header with Toggle Button -->
                        <div class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition" 
                             @click="filterOpen = !filterOpen">
                            <h3 class="text-sm font-semibold text-gray-600 flex items-center gap-2">
                                <i class="fas fa-filter"></i>
                                {{ __('messages.quick_filter') }}
                                <span class="text-xs text-gray-400">({{ count($allCategories) }} {{ __('messages.categories') }})</span>
                            </h3>
                            <button type="button" class="text-gray-600 hover:text-primary transition">
                                <i class="fas" :class="filterOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        </div>
                        
                        <!-- Filter Content -->
                        <div x-show="filterOpen" 
                             x-collapse
                             class="border-t border-gray-200">
                            <!-- Desktop: wrap -->
                            <div class="hidden md:block p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($allCategories as $category)
                                        <a href="#{{ $category['key'] }}" 
                                           class="px-3 py-2 {{ $category['type'] === 'sale' ? 'bg-primary/10 hover:bg-primary text-primary hover:text-white' : 'bg-green-50 hover:bg-green-600 text-green-700 hover:text-white' }} rounded-lg transition text-sm font-medium whitespace-nowrap {{ count($category['properties']) === 0 ? 'opacity-60' : '' }}">
                                            <i class="fas {{ $category['type'] === 'sale' ? 'fa-home' : 'fa-key' }} mr-1"></i>
                                            {{ $category['type_title'] }}: {{ $category['title'] }} ({{ count($category['properties']) }})
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Mobile: horizontal scroll -->
                            <div class="md:hidden p-4">
                                <div class="overflow-x-auto scrollbar-hide">
                                    <div class="flex gap-2">
                                        @foreach($allCategories as $category)
                                            <a href="#{{ $category['key'] }}" 
                                               class="flex-none px-3 py-2 {{ $category['type'] === 'sale' ? 'bg-primary/10 hover:bg-primary text-primary hover:text-white' : 'bg-green-50 hover:bg-green-600 text-green-700 hover:text-white' }} rounded-lg transition text-sm font-medium whitespace-nowrap {{ count($category['properties']) === 0 ? 'opacity-60' : '' }}">
                                                <i class="fas {{ $category['type'] === 'sale' ? 'fa-home' : 'fa-key' }} mr-1"></i>
                                                {{ $category['type_title'] }}: {{ $category['title'] }} ({{ count($category['properties']) }})
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- All Categories (Mixed) -->
                    <div>
                        @forelse($allCategories as $category)
                            <div id="{{ $category['key'] }}" class="bg-white rounded-xl shadow-md p-6 mb-6 scroll-mt-24">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold text-dark flex items-center gap-2">
                                        <i class="fas {{ $category['type'] === 'sale' ? 'fa-home text-primary' : 'fa-key text-green-600' }}"></i>
                                        <span class="{{ $category['type'] === 'sale' ? 'text-primary' : 'text-green-600' }}">{{ $category['type_title'] }}:</span>
                                        {{ $category['title'] }} ({{ count($category['properties']) }})
                                    </h3>
                                    @if(count($category['properties']) > 3)
                                        <div class="flex gap-2">
                                            <button onclick="scrollCarousel('{{ $category['key'] }}-carousel', 'left')" 
                                                    class="w-8 h-8 rounded-full {{ $category['type'] === 'sale' ? 'bg-primary/10 hover:bg-primary text-primary hover:text-white' : 'bg-green-100 hover:bg-green-600 text-green-700 hover:text-white' }} transition flex items-center justify-center">
                                                <i class="fas fa-chevron-left text-sm"></i>
                                            </button>
                                            <button onclick="scrollCarousel('{{ $category['key'] }}-carousel', 'right')" 
                                                    class="w-8 h-8 rounded-full {{ $category['type'] === 'sale' ? 'bg-primary/10 hover:bg-primary text-primary hover:text-white' : 'bg-green-100 hover:bg-green-600 text-green-700 hover:text-white' }} transition flex items-center justify-center">
                                                <i class="fas fa-chevron-right text-sm"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @if(count($category['properties']) > 0)
                                    <!-- Always use carousel layout for consistent UI -->
                                    <div id="{{ $category['key'] }}-carousel" class="overflow-x-auto scrollbar-hide scroll-smooth pb-2">
                                        <div class="flex gap-4">
                                            @foreach($category['properties'] as $property)
                                                <div class="flex-none w-[280px] sm:w-[320px]">
                                                    @include('components.property-card', ['property' => $property])
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500 text-center py-8">
                                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i><br>
                                        {{ __('messages.no_properties_in_category') }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8 bg-white rounded-xl">
                                {{ __('messages.no_categories_defined') }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Contact Bar (Mobile/Scroll) -->
    <div id="floating-contact-bar" 
         class="fixed bottom-0 left-0 right-0 bg-white shadow-2xl border-t-2 border-primary z-50 transform translate-y-full transition-transform duration-300">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between gap-3">
                <!-- Agent Name -->
                <div class="hidden sm:flex items-center gap-3 flex-1">
                    @if($team->image)
                        <img src="{{ asset('portal/' . $team->image) }}" 
                             alt="{{ $team->name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-primary">
                    @else
                        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    @endif
                    <div>
                        <p class="font-bold text-dark">{{ $team->name }}</p>
                        <p class="text-xs text-gray-600">{{ __('messages.contact_agent') }}</p>
                    </div>
                </div>

                <!-- Contact Buttons -->
                <div class="flex items-center gap-2 sm:gap-3 flex-1 sm:flex-initial justify-end">
                    @if($team->phone)
                        <a href="tel:{{ $team->phone }}" 
                           class="flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white px-4 py-3 rounded-lg transition font-medium shadow-lg hover:shadow-xl">
                            <i class="fas fa-phone"></i>
                            <span class="hidden sm:inline">{{ __('messages.call') }}</span>
                        </a>
                    @endif

                    @if($team->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $team->whatsapp) }}" 
                           target="_blank"
                           class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition font-medium shadow-lg hover:shadow-xl">
                            <i class="fab fa-whatsapp"></i>
                            <span class="hidden sm:inline">WhatsApp</span>
                        </a>
                    @endif

                    @if($team->email)
                        <a href="mailto:{{ $team->email }}" 
                           class="flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-800 text-white px-4 py-3 rounded-lg transition font-medium shadow-lg hover:shadow-xl">
                            <i class="fas fa-envelope"></i>
                            <span class="hidden sm:inline">{{ __('messages.email') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Carousel scroll function
        function scrollCarousel(carouselId, direction) {
            const carousel = document.getElementById(carouselId);
            const scrollAmount = 300; // pixels to scroll
            
            if (direction === 'left') {
                carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }
        
        // Show/hide floating contact bar based on scroll position
        let lastScrollTop = 0;
        const floatingBar = document.getElementById('floating-contact-bar');
        const showBarAfter = 300; // Show after scrolling 300px

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Show bar when scrolled past threshold
            if (scrollTop > showBarAfter) {
                floatingBar.classList.remove('translate-y-full');
            } else {
                floatingBar.classList.add('translate-y-full');
            }
            
            lastScrollTop = scrollTop;
        });

        // Also show on mobile/tablet from the start if viewport is small
        if (window.innerWidth < 1024) {
            setTimeout(() => {
                floatingBar.classList.remove('translate-y-full');
            }, 1000); // Show after 1 second on small screens
        }
    </script>
    
    <style>
        /* Hide scrollbar but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Smooth scroll offset for anchor links */
        .scroll-mt-24 {
            scroll-margin-top: 6rem;
        }
    </style>
    @endpush
</x-app-layout>
