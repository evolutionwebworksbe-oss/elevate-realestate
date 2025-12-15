<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('site_favicon', 'favicon.ico')) }}">

    {!! SEO::generate() !!}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: #2B6B7F;
            --color-primary-dark: #1A4D5C;
            --color-accent: #A4CE4E;
            --color-light: #E8F4F7;
            --color-gray: #6B7280;
            --color-dark: #374151;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false, searchOpen: false }" class="bg-white shadow-md sticky top-0 z-50">
        <!-- Top Bar - Secondary Navigation -->
        <div class="hidden lg:block bg-primary-dark">
            <div class="max-w-[1400px] mx-auto px-4">
                <div class="flex justify-end items-center py-2">
                    <div class="flex items-center gap-6 text-sm text-white">
                        <x-topbar-menu :menu="$topbarMenu" />
                        
                        <!-- Language Switcher -->
                        <div class="flex items-center gap-2 ml-2 pl-6 border-l border-white/20">
                            <a href="{{ route('lang.switch', 'nl') }}" 
                            class="px-3 py-1 rounded {{ app()->getLocale() == 'nl' ? 'bg-accent text-primary-dark' : 'text-white hover:bg-white/10' }} transition text-sm font-medium">
                                NL
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" 
                            class="px-3 py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-accent text-primary-dark' : 'text-white hover:bg-white/10' }} transition text-sm font-medium">
                                EN
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="max-w-[1400px] mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0">
                        <img src="{{ asset(\App\Models\Setting::get('site_logo_menu', 'portal/img/logo.png')) }}" alt="Elevate Real Estate" class="h-7 md:h-9">
                    </a>
                </div>

                <!-- Desktop Main Navigation -->
                <div class="hidden lg:flex items-center gap-6 xl:gap-8 ml-auto">
                    <x-menu-items :menu="$mainMenu" />
                </div>

                <!-- Search & Mobile Menu Button -->
                <div class="flex items-center gap-4 ml-6">
                    <button @click="searchOpen = !searchOpen" class="text-primary hover:text-primary-dark transition">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-primary">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modern Mobile Quick Links -->
        <div class="lg:hidden bg-gradient-to-r from-[#2B6B7F] to-[#1A4D5C] shadow-md">
            <div class="px-3 py-3">
                <div class="grid grid-cols-4 gap-2">
                    <!-- Te Koop -->
                    <a href="{{ route('properties.sale') }}" 
                       class="flex flex-col items-center justify-center h-16 rounded-xl transition-all duration-300 {{ request()->routeIs('properties.sale') ? 'bg-[#A4CE4E] text-[#1A4D5C] scale-105 shadow-lg' : 'bg-white/15 backdrop-blur-sm text-white hover:bg-white/25' }}">
                        <i class="fas fa-home text-lg mb-0.5 {{ request()->routeIs('properties.sale') ? 'text-[#1A4D5C]' : 'text-[#A4CE4E]' }}"></i>
                        <span class="text-[9px] font-semibold text-center leading-tight">Te Koop</span>
                    </a>
                    
                    <!-- Te Huur -->
                    <a href="{{ route('properties.rent') }}" 
                       class="flex flex-col items-center justify-center h-16 rounded-xl transition-all duration-300 {{ request()->routeIs('properties.rent') ? 'bg-[#A4CE4E] text-[#1A4D5C] scale-105 shadow-lg' : 'bg-white/15 backdrop-blur-sm text-white hover:bg-white/25' }}">
                        <i class="fas fa-key text-lg mb-0.5 {{ request()->routeIs('properties.rent') ? 'text-[#1A4D5C]' : 'text-[#A4CE4E]' }}"></i>
                        <span class="text-[9px] font-semibold text-center leading-tight">Te Huur</span>
                    </a>
                    
                    <!-- Corporate -->
                    <a href="{{ route('properties.corporate') }}" 
                       class="flex flex-col items-center justify-center h-16 rounded-xl transition-all duration-300 {{ request()->routeIs('properties.corporate') ? 'bg-[#A4CE4E] text-[#1A4D5C] scale-105 shadow-lg' : 'bg-white/15 backdrop-blur-sm text-white hover:bg-white/25' }}">
                        <i class="fas fa-building text-lg mb-0.5 {{ request()->routeIs('properties.corporate') ? 'text-[#1A4D5C]' : 'text-[#A4CE4E]' }}"></i>
                        <span class="text-[9px] font-semibold text-center leading-tight">Corporate</span>
                    </a>
                    
                    <!-- Door Eigenaar -->
                    <a href="{{ route('properties.by-owner') }}" 
                       class="flex flex-col items-center justify-center h-16 rounded-xl transition-all duration-300 {{ request()->routeIs('properties.by-owner') ? 'bg-[#A4CE4E] text-[#1A4D5C] scale-105 shadow-lg' : 'bg-white/15 backdrop-blur-sm text-white hover:bg-white/25' }}">
                        <i class="fas fa-user text-lg mb-0.5 {{ request()->routeIs('properties.by-owner') ? 'text-[#1A4D5C]' : 'text-[#A4CE4E]' }}"></i>
                        <span class="text-[9px] font-semibold text-center leading-tight px-1">Eigenaar</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Search Overlay -->
        <div x-show="searchOpen" 
             x-transition
             @click.away="searchOpen = false"
             class="absolute top-full left-0 w-full bg-white shadow-lg py-6 z-50"
             x-data="{
                 searchQuery: '',
                 results: [],
                 loading: false,
                 timeout: null,
                 async search() {
                     if (this.timeout) clearTimeout(this.timeout);
                     if (this.searchQuery.length < 2) {
                         this.results = [];
                         return;
                     }
                     this.loading = true;
                     this.timeout = setTimeout(async () => {
                         try {
                             const response = await fetch(`/api/properties/live-search?q=${encodeURIComponent(this.searchQuery)}`);
                             const data = await response.json();
                             this.results = data;
                         } catch (error) {
                             console.error('Search error:', error);
                             this.results = [];
                         } finally {
                             this.loading = false;
                         }
                     }, 300);
                 }
             }">
            <div class="container mx-auto px-4">
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery"
                           @input="search()"
                           placeholder="{{ __('messages.search_placeholder') }}"
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:border-primary">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fas fa-search text-gray-400" x-show="!loading"></i>
                        <i class="fas fa-spinner fa-spin text-primary" x-show="loading"></i>
                    </div>
                </div>
                
                <!-- Live Search Results -->
                <div x-show="results.length > 0" 
                     x-transition
                     class="mt-4 bg-white rounded-lg border max-h-96 overflow-y-auto">
                    <template x-for="result in results" :key="result.id">
                        <a :href="result.url" 
                           class="flex items-center gap-4 p-4 hover:bg-gray-50 border-b last:border-b-0 transition">
                            <template x-if="result.image">
                                <img :src="result.image" 
                                     :alt="result.title"
                                     class="w-20 h-20 object-cover rounded">
                            </template>
                            <template x-if="!result.image">
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-home text-gray-400"></i>
                                </div>
                            </template>
                            <div class="flex-1">
                                <h3 class="font-semibold text-dark" x-text="result.title"></h3>
                                <p class="text-sm text-gray-600" x-text="result.district"></p>
                                <p class="text-primary font-semibold" x-text="result.currency + ' ' + result.price"></p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </template>
                </div>
                
                <!-- No Results Message -->
                <div x-show="searchQuery.length >= 2 && results.length === 0 && !loading"
                     class="mt-4 p-8 bg-gray-50 rounded-lg text-center text-gray-600">
                    <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                    <p>{{ __('messages.no_results') }}</p>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition
             class="lg:hidden border-t">
            <div class="container mx-auto px-4 py-4 space-y-2">
                <x-mobile-menu :mainMenu="$mainMenu" :topbarMenu="$topbarMenu" />
                
                <!-- Language Switcher - Mobile -->
                <div class="flex gap-2 pt-3 border-t">
                    <a href="{{ route('lang.switch', 'nl') }}" 
                       class="flex-1 px-3 py-2 rounded text-center {{ app()->getLocale() == 'nl' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }} transition text-sm font-medium">
                        Nederlands
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="flex-1 px-3 py-2 rounded text-center {{ app()->getLocale() == 'en' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600' }} transition text-sm font-medium">
                        English
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-primary-dark text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <img src="{{ asset(\App\Models\Setting::get('site_logo_footer', 'portal/img/logo.png')) }}" alt="Elevate" class="h-12 mb-4 brightness-0 invert">
                    <p class="text-gray-300 text-sm mb-4">
                        {{ __('messages.footer_tagline') }}
                    </p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/elevaterealestatenv" class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-accent transition">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/elevaterealestatenv/" class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-accent transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send/?phone=5978180018&text&app_absent=0" class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-accent transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('messages.quick_links') }}</h3>
                    <ul class="space-y-2 text-gray-300 text-sm">
                        <li><a href="{{ route('properties.sale') }}" class="hover:text-accent transition">{{ __('messages.for_sale') }}</a></li>
                        <li><a href="{{ route('properties.rent') }}" class="hover:text-accent transition">{{ __('messages.for_rent') }}</a></li>
                        <li><a href="{{ route('properties.corporate') }}" class="hover:text-accent transition">{{ __('messages.corporate') }}</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-accent transition">{{ __('messages.about') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-accent transition">{{ __('messages.contact') }}</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('messages.contact') }}</h3>
                    <ul class="space-y-3 text-gray-300 text-sm">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt mt-1"></i>
                            <span>Frederik Derbystraat no.78<br>Paramaribo, Suriname</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone"></i>
                            <a href="tel:+5978180018" class="hover:text-accent transition">+597 8180018</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@elevaterealestate.sr" class="hover:text-accent transition">info@elevaterealestate.sr</a>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ __('messages.newsletter') }}</h3>
                    <p class="text-gray-300 text-sm mb-4">
                        {{ __('messages.newsletter_text') }}
                    </p>
                    
                    @if(session('success'))
                        <p class="text-green-400 text-sm mb-2">{{ session('success') }}</p>
                    @endif
                    
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="email" 
                            name="email"
                            placeholder="{{ __('messages.your_email') }}" 
                            required
                            class="flex-1 px-3 py-2 rounded text-dark text-sm focus:outline-none">
                        <button type="submit" class="px-4 py-2 bg-accent rounded hover:bg-opacity-90 transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Elevate Real Estate. {{ __('messages.all_rights_reserved') }}</p>
                <p class="mt-2">Built by <a href="https://www.evolutionwebworks.be/" target="_blank" class="text-accent hover:text-white transition">Evolution Web Works</a></p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>