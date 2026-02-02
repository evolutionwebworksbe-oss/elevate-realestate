<x-app-layout>
    @php
        $locale = app()->getLocale();
        $propertyName = $locale == 'en' && $property->naam_en ? $property->naam_en : $property->naam;
        $propertyDescription = $locale == 'en' && $property->omschrijving_en ? $property->omschrijving_en : $property->omschrijving;
        
        // Translate Object Type
        $objectTypeName = $property->objectType->naam ?? '';
        if ($locale == 'en') {
            $objectTypeTranslations = [
                'Te Koop' => 'For Sale',
                'Te Huur' => 'For Rent',
            ];
            $objectTypeName = $objectTypeTranslations[$objectTypeName] ?? $objectTypeName;
        }
        
        // Translate Object SubType
        $objectSubTypeName = $property->objectSubType->naam ?? '';
        if ($locale == 'en') {
            $objectSubTypeTranslations = [
                'Woningen' => 'Houses',
                'Percelen' => 'Lots',
                'Panden' => 'Buildings',
                'Appartementen' => 'Apartments',
                'Kantoren' => 'Offices',
                'Bar/Restaurant' => 'Bar/Restaurant',
                'Kantoor met werkloods' => 'Office with Warehouse',
            ];
            $objectSubTypeName = $objectSubTypeTranslations[$objectSubTypeName] ?? $objectSubTypeName;
        }
        
        $allImages = collect([$property->featuredFoto])->merge($property->images->pluck('url'))->filter();
        $videoId = $property->youtube ?? '';
        $hasVideo = !empty($videoId);
        
        // Prepare description for meta tags
        $metaDescription = Str::limit(strip_tags($propertyDescription), 160);
        $featuredImage = $property->featuredFoto ? asset('portal' . $property->featuredFoto) : asset('portal/img/logo.png');
        $currentUrl = url()->current();
    @endphp
    
    @push('styles')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:title" content="{{ $propertyName }} - {{ $objectTypeName }} - Elevate Real Estate">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $featuredImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Elevate Real Estate">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $currentUrl }}">
    <meta property="twitter:title" content="{{ $propertyName }} - {{ $objectTypeName }}">
    <meta property="twitter:description" content="{{ $metaDescription }}">
    <meta property="twitter:image" content="{{ $featuredImage }}">
    
    <!-- Additional Property Info -->
    <meta property="og:locale" content="{{ $locale == 'nl' ? 'nl_SR' : 'en_US' }}">
    <meta property="og:price:amount" content="{{ $property->vraagPrijs }}">
    <meta property="og:price:currency" content="{{ $property->currencyRelation->code ?? 'USD' }}">
    @if($property->district)
    <meta property="og:locality" content="{{ $property->district->naam }}">
    @endif
    <meta property="og:country-name" content="Suriname">
    @endpush
    
    <x-slot name="title">{{ $propertyName }} - Elevate Real Estate</x-slot>
    
    <!-- Breadcrumbs -->
    <div class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <nav class="text-sm">
                <ol class="flex items-center gap-2 text-gray-600">
                    <li><a href="{{ route('home') }}" class="hover:text-primary">{{ __('messages.home') }}</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li><a href="{{ route('properties.sale') }}" class="hover:text-primary">{{ __('messages.properties') }}</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="text-primary font-medium truncate">{{ $propertyName }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8" x-data="propertyDetail()">
    
    <!-- Image Modal -->
    <div x-show="imageModalOpen" 
        x-transition
        @click="closeImageModal()"
        @keydown.escape.window="closeImageModal()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4">
        <button @click="closeImageModal()" 
                class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="relative max-w-6xl max-h-full" @click.stop>
            <img :src="modalImageUrl" 
                alt="Full size image" 
                class="max-w-full max-h-[90vh] object-contain mx-auto">
            
            <!-- Modal Navigation -->
            @if($allImages->count() > 1)
            <button @click.stop="previousModalImage()" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full text-gray-800 transition flex items-center justify-center">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button @click.stop="nextModalImage()" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full text-gray-800 transition flex items-center justify-center">
                <i class="fas fa-chevron-right"></i>
            </button>
            @endif
        </div>
    </div>
    
    <!-- Main Image Slider -->
    <div class="relative mb-8 rounded-2xl overflow-hidden bg-gray-100" style="height: 500px;">
        <!-- Images -->
        @foreach($allImages as $index => $imageUrl)
        <div x-show="currentSlide === {{ $index }}" 
             x-transition:enter="transition ease-in-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="absolute inset-0 flex items-center justify-center cursor-pointer"
             @click="openImageModal('{{ asset('portal' . $imageUrl) }}', {{ $index }})">
            <img src="{{ asset('portal' . $imageUrl) }}" 
                 alt="{{ $propertyName }}"
                 class="w-full h-full object-contain">
        </div>
        @endforeach

        <!-- Video Slide -->
        @if($hasVideo && $videoId)
        <div x-show="currentSlide === {{ $allImages->count() }}" 
             x-transition:enter="transition ease-in-out duration-500"
             class="absolute inset-0 bg-gray-900 flex items-center justify-center">
            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                    class="w-full h-full" 
                    width="100%" 
                    height="100%"
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen></iframe>
        </div>
        @endif

        <!-- Navigation Arrows -->
        @if($allImages->count() + ($hasVideo && $videoId ? 1 : 0) > 1)
        <button @click="previousSlide()" 
                class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full text-gray-800 transition flex items-center justify-center shadow-lg z-10">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button @click="nextSlide()" 
                class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full text-gray-800 transition flex items-center justify-center shadow-lg z-10">
            <i class="fas fa-chevron-right"></i>
        </button>
        @endif

        <!-- Badges -->
        <div class="absolute top-4 left-4 flex flex-wrap gap-2 z-10">
            <span class="px-4 py-2 bg-accent text-white text-sm font-bold rounded-lg">
                {{ $objectTypeName }}
            </span>

            @if($property->objectSubType)
                <span class="px-4 py-2 bg-white bg-opacity-90 text-gray-800 text-sm font-bold rounded-lg">
                    {{ $objectSubTypeName }}
                </span>
            @endif
            
            @if($property->featured)
                <span class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg">
                    <i class="fas fa-star"></i> Featured
                </span>
            @endif
        </div>

        <!-- Status Badge - Top Right -->
        @if($property->status == 2)
            <div class="absolute top-4 right-4 z-10">
                @php
                    $isSale = $property->objectType && str_contains(strtolower($property->objectType->naam), 'koop');
                    $statusLabel = $isSale ? __('messages.sold') : __('messages.rented');
                @endphp
                <span class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg">
                    {{ strtoupper($statusLabel) }}
                </span>
            </div>
        @elseif($property->status == 3)
            <div class="absolute top-4 right-4 z-10">
                <span class="px-4 py-2 bg-yellow-500 text-white text-sm font-bold rounded-lg">
                    {{ strtoupper(__('messages.reserved')) }}
                </span>
            </div>
        @endif

        <!-- Slide Counter -->
        <div class="absolute bottom-4 right-4 flex items-center gap-2 z-10">
            <template x-if="showVideo">
                <span class="px-4 py-2 bg-white bg-opacity-90 text-gray-800 rounded-lg text-sm font-semibold">
                    <i class="fas fa-play-circle mr-1"></i> {{ __('messages.video_tour') }}
                </span>
            </template>
            <span class="px-4 py-2 bg-white bg-opacity-90 text-gray-800 rounded-lg text-sm font-semibold">
                <span x-text="currentSlide + 1"></span> / <span x-text="totalSlides"></span>
            </span>
        </div>

        <!-- View Full Image Button (Mobile) -->
        <button @click="openImageModal('{{ asset('portal' . ($allImages->first() ?? '')) }}', currentSlide)" 
                x-show="currentSlide < {{ $allImages->count() }}"
                class="absolute bottom-4 left-4 lg:hidden px-4 py-2 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 rounded-lg transition z-10">
            <i class="fas fa-expand"></i> {{ __('messages.full_view') }}
        </button>
    </div>

    <!-- Thumbnail Strip -->
    @if($allImages->count() + ($hasVideo ? 1 : 0) > 1)
    <div class="mb-8 overflow-x-auto">
        <div class="flex gap-2 pb-2">
            @foreach($allImages as $index => $imageUrl)
            <button @click="currentSlide = {{ $index }}; showVideo = false" 
                    :class="currentSlide === {{ $index }} ? 'ring-2 ring-primary' : ''"
                    class="flex-shrink-0 w-24 h-16 rounded-lg overflow-hidden transition">
                <img src="{{ asset('portal' . $imageUrl) }}" 
                     alt="Thumbnail" 
                     class="w-full h-full object-cover">
            </button>
            @endforeach
            
            @if($hasVideo && $videoId)
            <button @click="currentSlide = {{ $allImages->count() }}; showVideo = true" 
                    :class="currentSlide === {{ $allImages->count() }} ? 'ring-2 ring-primary' : ''"
                    class="flex-shrink-0 w-24 h-16 rounded-lg overflow-hidden bg-gray-800 flex items-center justify-center transition hover:bg-gray-700">
                <i class="fas fa-play-circle text-white text-2xl"></i>
            </button>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Main Content -->
        <div class="lg:col-span-2">
            
            <!-- Header -->
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-4xl font-bold text-dark mb-2">{{ $propertyName }}</h1>
                        <div class="flex items-center gap-2 text-gray-600 text-sm md:text-base">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <span>{{ $property->district->naam ?? '' }}{{ $property->omgeving ? ', ' . $property->omgeving->naam : '' }}</span>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        @if($property->discount && $property->discount > 0 && $property->discount < $property->vraagPrijs)
                            <div class="text-2xl md:text-3xl font-bold text-primary">
                                {{ $property->currencyRelation->name ?? 'EURO' }} {{ number_format($property->discount, 0, ',', '.') }}
                            </div>
                            <div class="text-base md:text-lg text-gray-400 line-through">
                                {{ number_format($property->vraagPrijs, 0, ',', '.') }}
                            </div>
                        @else
                            <div class="text-2xl md:text-3xl font-bold text-primary">
                                {{ $property->currencyRelation->name ?? 'EURO' }} {{ number_format($property->vraagPrijs, 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Property ID & Get Directions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div class="text-sm text-gray-500">
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude ?? '5.8520' }},{{ $property->longitude ?? '-55.2038' }}" 
                    target="_blank"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition text-sm">
                        <i class="fas fa-directions"></i>
                        <span>{{ __('messages.get_directions') }}</span>
                    </a>
                </div>

                <!-- Property Details Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    @if($property->status)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.status') }}</div>
                        <div class="font-semibold text-dark text-sm">
                            @if($property->status == 1)
                                {{ __('messages.available') }}
                            @elseif($property->status == 2)
                                {{ __('messages.sold_rented') }}
                            @elseif($property->status == 3)
                                {{ __('messages.reserved') }}
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($property->aantalSlaapkamers)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.bedrooms') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->aantalSlaapkamers }}</div>
                    </div>
                    @endif

                    @if($property->aantalBadkamers)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.bathrooms') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->aantalBadkamers }}</div>
                    </div>
                    @endif

                    @if($property->woonOppervlakte)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.living_area_label') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->woonOppervlakte }} M2</div>
                    </div>
                    @endif

                    @if($property->perceel)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.plot') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->perceel }} M2</div>
                    </div>
                    @endif

                    @if($property->gemeubileerd)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.furnished') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->gemeubileerd == 1 ? __('messages.yes') : __('messages.no') }}</div>
                    </div>
                    @endif

                    @if($property->titel)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.title') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->titel->naam }}</div>
                    </div>
                    @endif

                    @if($property->bouwjaar)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.year_built') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->bouwjaar }}</div>
                    </div>
                    @endif

                    @if($property->aantalVerdiepingen)
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <div class="text-xs text-gray-500 mb-1">{{ __('messages.floors') }}</div>
                        <div class="font-semibold text-dark text-sm">{{ $property->aantalVerdiepingen }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.description') }}</h2>
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $propertyDescription ?? __('messages.no_description') }}
                    </div>
                </div>
            </div>

            <!-- Features -->
            @if($property->voorzieningen->count() > 0 || $property->beveiliging->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.features_security') }}</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($property->voorzieningen as $voorziening)
                    <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-check-circle text-accent"></i>
                        <span class="text-gray-700 text-sm">{{ $voorziening->naam }}</span>
                    </div>
                    @endforeach

                    @foreach($property->beveiliging as $item)
                    <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-shield-alt text-accent"></i>
                        <span class="text-gray-700 text-sm">{{ $item->naam }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Extra Rooms -->
            @if($property->extraRuimtes->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.extra_rooms') }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($property->extraRuimtes as $ruimte)
                    <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-door-open text-primary"></i>
                        <span class="text-gray-700 text-sm">{{ $ruimte->naam }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Additional Property Details -->
            @if($property->details)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.additional_details') }}</h2>
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        @if($property->details->woonlagen)
                        <div class="p-4 border-b md:border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-layer-group text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.living_floors') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->woonlagen }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($property->details->woonkamer_count)
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-couch text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.living_rooms') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->woonkamer_count }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($property->details->keuken_count)
                        <div class="p-4 border-b md:border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-utensils text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.kitchens') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->keuken_count }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($property->details->toiletten_count)
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-restroom text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.toilets') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->toiletten_count }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($property->details->parkeergelegenheid_type)
                        <div class="p-4 border-b md:border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-parking text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.parking_type') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->parkeergelegenheid_type }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($property->details->parkeerplaatsen_aantal)
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-car text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.parking_spaces') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->parkeerplaatsen_aantal }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($property->details->airco_algemeen)
                        <div class="p-4 md:border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-wind text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.air_conditioning') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->airco_algemeen }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($property->details->airco_locaties)
                        <div class="p-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-snowflake text-primary text-xl"></i>
                                <div>
                                    <div class="text-sm text-gray-600">{{ __('messages.ac_locations') }}</div>
                                    <div class="font-semibold text-dark">{{ $property->details->airco_locaties }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Video Section -->
            @if($hasVideo && $videoId)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.video_tour') }}</h2>
                <div class="aspect-video rounded-2xl overflow-hidden bg-gray-900">
                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                            class="w-full h-full" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                </div>
            </div>
            @endif

            <!-- Map -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.location') }}</h2>
                <div id="map" class="w-full h-96 rounded-xl bg-gray-100 relative z-0"></div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                
                <!-- Agent Card -->
                @if($property->teamMembers->count() > 0)
                    @php $agent = $property->teamMembers->first(); @endphp
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-dark mb-4">{{ __('messages.contact_us') }}</h3>
                        
                        <div class="flex items-center gap-4 mb-6">
                            @if($agent->image)
                                <img src="{{ asset('portal/' . $agent->image) }}" 
                                    alt="{{ $agent->name }}"
                                    class="w-20 h-20 rounded-full object-cover">
                            @else
                                <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($agent->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="font-bold text-dark text-lg">{{ $agent->name }}</div>
                                @php $agentTitles = $agent->getAllTitles(); @endphp
                                @if($agentTitles->count() > 0)
                                    <div class="text-sm text-gray-600">{{ $agentTitles->pluck('name')->join(' • ') }}</div>
                                @else
                                    <div class="text-sm text-gray-600">{{ __('messages.agent') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            @if($agent->phone)
                            <a href="tel:{{ $agent->phone }}" 
                            class="flex items-center gap-3 text-gray-700 hover:text-primary transition">
                                <i class="fas fa-phone text-gray-400"></i>
                                <span class="text-sm">{{ $agent->phone }}</span>
                            </a>
                            @endif

                            @if($agent->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agent->whatsapp) }}" 
                            target="_blank"
                            class="flex items-center gap-3 text-gray-700 hover:text-primary transition">
                                <i class="fab fa-whatsapp text-gray-400"></i>
                                <span class="text-sm">{{ $agent->whatsapp }}</span>
                            </a>
                            @endif

                            @if($agent->email)
                            <a href="mailto:{{ $agent->email }}" 
                            class="flex items-center gap-3 text-gray-700 hover:text-primary transition">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <span class="text-sm truncate">{{ $agent->email }}</span>
                            </a>
                            @endif
                        </div>

 
                        <a href="{{ route('team.profile', $agent) }}" 
                        class="mb-3 w-full bg-gray-100 hover:bg-gray-200 text-dark py-3 px-4 rounded-xl font-semibold flex items-center justify-center gap-2 transition">
                            <i class="fas fa-user"></i>
                            {{ __('messages.view_profile') }}
                        </a>

                        @if($agent->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agent->whatsapp) }}?text={{ urlencode(__('messages.whatsapp_message_template', ['property' => $propertyName, 'url' => url()->current()])) }}" 
                        target="_blank"
                        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-xl font-semibold flex items-center justify-center gap-2 transition">
                            <i class="fab fa-whatsapp text-xl"></i>
                            {{ __('messages.whatsapp_message') }}
                        </a>
                        @endif

                        <a href="tel:{{ $agent->phone ?? '' }}" 
                        class="mt-3 w-full bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-xl font-semibold flex items-center justify-center gap-2 transition">
                            <i class="fas fa-phone"></i>
                            {{ __('messages.call_now') }}
                        </a>
                    </div>
                @endif

                <!-- Share -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-dark mb-4">{{ __('messages.share_property') }}</h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                        target="_blank"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-xl text-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($propertyName) }}" 
                        target="_blank"
                        class="flex-1 bg-sky-500 text-white py-3 rounded-xl text-center hover:bg-sky-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('{{ __('messages.link_copied') }}')" 
                                class="flex-1 bg-gray-600 text-white py-3 rounded-xl text-center hover:bg-gray-700 transition">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>

                <!-- Similar Properties - Desktop Only -->
                @if($similarProperties->count() > 0)
                <div class="hidden lg:block bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-dark mb-4">{{ __('messages.similar_properties') }}</h3>
                    <div class="space-y-4">
                        @foreach($similarProperties->take(3) as $similar)
                        <a href="{{ route('properties.show', $similar) }}" class="block group">
                            <div class="flex gap-3">
                                <div class="w-24 h-20 flex-shrink-0 rounded-lg overflow-hidden">
                                    @if($similar->featuredFoto)
                                        <img src="{{ asset('portal' . $similar->featuredFoto) }}" 
                                            alt="{{ $similar->naam }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    @else
                                        <div class="w-full h-full bg-gray-200"></div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-dark text-sm group-hover:text-primary transition line-clamp-2 mb-1">
                                        {{ $similar->naam }}
                                    </div>
                                    <div class="text-xs text-gray-500 mb-2">
                                        {{ $similar->district->naam ?? '' }}
                                    </div>
                                    <div class="text-sm font-bold text-primary">
                                        ${{ number_format($similar->vraagPrijs, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Similar Properties - Mobile Only -->
    @if($similarProperties->count() > 0)
    <div class="mt-12 lg:hidden">
        <h2 class="text-2xl font-bold text-dark mb-6">{{ __('messages.similar_properties') }}</h2>
        <div class="grid grid-cols-1 gap-6">
            @foreach($similarProperties as $similar)
                <x-property-card :property="$similar" />
            @endforeach
        </div>
    </div>
    @endif

    <!-- Similar Properties - Desktop -->
    @if($similarProperties->count() > 3)
    <div class="mt-16 hidden lg:block">
        <h2 class="text-3xl font-bold text-dark mb-8">{{ __('messages.similar_properties') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($similarProperties as $similar)
                <x-property-card :property="$similar" />
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function propertyDetail() {
    const allImages = @json($allImages->map(fn($img) => asset('portal' . $img))->values());
    
    return {
        currentSlide: 0,
        totalSlides: {{ $allImages->count() + ($hasVideo && $videoId ? 1 : 0) }},
        showVideo: false,
        imageModalOpen: false,
        modalImageUrl: '',
        modalImageIndex: 0,
        
        init() {
            this.$watch('imageModalOpen', value => {
                if (!value) {
                    document.body.style.overflow = '';
                    document.body.style.position = '';
                    document.body.style.width = '';
                }
            });
        },
        
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            this.showVideo = (this.currentSlide === {{ $allImages->count() }} && {{ $hasVideo && $videoId ? 'true' : 'false' }});
        },
        
        previousSlide() {
            this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
            this.showVideo = (this.currentSlide === {{ $allImages->count() }} && {{ $hasVideo && $videoId ? 'true' : 'false' }});
        },
        
        openImageModal(imageUrl, index) {
            if (this.currentSlide < {{ $allImages->count() }}) {
                this.modalImageUrl = imageUrl;
                this.modalImageIndex = index;
                this.imageModalOpen = true;
                document.body.style.overflow = 'hidden';
            }
        },
        
        closeImageModal() {
            this.imageModalOpen = false;
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.width = '';
        },
        
        nextModalImage() {
            this.modalImageIndex = (this.modalImageIndex + 1) % allImages.length;
            this.modalImageUrl = allImages[this.modalImageIndex];
        },
        
        previousModalImage() {
            this.modalImageIndex = this.modalImageIndex === 0 ? allImages.length - 1 : this.modalImageIndex - 1;
            this.modalImageUrl = allImages[this.modalImageIndex];
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([{{ $property->latitude ?? '5.8520' }}, {{ $property->longitude ?? '-55.2038' }}], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([{{ $property->latitude ?? '5.8520' }}, {{ $property->longitude ?? '-55.2038' }}]).addTo(map)
        .bindPopup('{{ $propertyName }}').openPopup();
});
</script>
@endpush
</x-app-layout>