<x-app-layout>
    <x-slot name="title">Home - Elevate Real Estate Suriname</x-slot>

    <!-- Hero Section with Dynamic Slider -->
    @if($sliders->count() > 0)
    <section class="relative" x-data="{ currentSlide: 0 }" 
            x-init="setInterval(() => { currentSlide = (currentSlide + 1) % {{ $sliders->count() }} }, 5000)">
        
        <div class="absolute inset-0 h-[600px]">
            @foreach($sliders as $index => $slide)
                <div x-show="currentSlide === {{ $index }}" 
                    x-transition:enter="transition ease-in-out duration-1000"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="absolute inset-0">
                    <img src="{{ asset('portal/' . $slide->image) }}" class="w-full h-full object-cover" alt="{{ $slide->title }}">
                    <div class="absolute inset-0 bg-black opacity-50"></div>
                </div>
            @endforeach
        </div>
        
        <div class="relative container mx-auto px-4 py-20 md:py-32 h-[600px] flex items-center">
            <div class="max-w-3xl">
                @foreach($sliders as $index => $slide)
                    <div x-show="currentSlide === {{ $index }}" 
                        x-transition:enter="transition ease-in duration-500"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <h1 class="text-4xl md:text-6xl font-bold mb-4 text-white">
                            {{ app()->getLocale() == 'en' && $slide->title_en ? $slide->title_en : $slide->title }}
                        </h1>
                        @if($slide->description || $slide->description_en)
                            <p class="text-xl md:text-2xl mb-8 text-gray-200">
                                {{ app()->getLocale() == 'en' && $slide->description_en ? $slide->description_en : $slide->description }}
                            </p>
                        @endif
                    </div>
                @endforeach

                <!-- Quick Search Form -->
                <div class="bg-white rounded-2xl shadow-2xl p-8">
                    <form action="{{ route('properties.search') }}" method="GET" 
                        x-data="{ 
                            selectedDistrict: '', 
                            omgevingen: [],
                            async loadOmgevingen() {
                                if (!this.selectedDistrict) {
                                    this.omgevingen = [];
                                    return;
                                }
                                try {
                                    const response = await fetch('/api/omgevingen?district_id=' + this.selectedDistrict);
                                    this.omgevingen = await response.json();
                                } catch (error) {
                                    console.error('Error:', error);
                                    this.omgevingen = [];
                                }
                            }
                        }">
                        <div class="grid grid-cols-1 gap-4" :class="omgevingen.length > 0 ? 'md:grid-cols-4' : 'md:grid-cols-3'">
                            <div>
                                <label for="object_type" class="block text-sm font-semibold text-gray-700 mb-3">{{ __('messages.type') }}</label>
                                <select name="object_type" id="object_type" class="w-full">
                                    <option value="">{{ __('messages.all_types') }}</option>
                                    @foreach($objectTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->naam }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="district_id" class="block text-sm font-semibold text-gray-700 mb-3">{{ __('messages.district') }}</label>
                                <select name="district_id" id="district_id" x-model="selectedDistrict" @change="loadOmgevingen()" class="w-full">
                                    <option value="">{{ __('messages.all_districts') }}</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->naam }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="omgevingen.length > 0" x-transition>
                                <label for="omgeving_id" class="block text-sm font-semibold text-gray-700 mb-3">{{ __('messages.area') }}</label>
                                <select name="omgeving_id" id="omgeving_id" class="w-full">
                                    <option value="">{{ __('messages.all_areas') }}</option>
                                    <template x-for="omgeving in omgevingen" :key="omgeving.id">
                                        <option :value="omgeving.id" x-text="omgeving.naam"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">&nbsp;</label>
                                <button type="submit" class="btn-accent w-full">
                                    <i class="fas fa-search mr-2"></i> {{ __('messages.search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Slider Indicators -->
        @if($sliders->count() > 1)
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
            @foreach($sliders as $index => $slide)
                <button @click="currentSlide = {{ $index }}" 
                        :class="currentSlide === {{ $index }} ? 'bg-accent' : 'bg-white bg-opacity-50'"
                        class="w-3 h-3 rounded-full transition"></button>
            @endforeach
        </div>
        @endif
    </section>
    @else
    <!-- Fallback Hero when no sliders -->
    <section class="relative bg-primary-dark">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative container mx-auto px-4 py-20 md:py-32 h-[600px] flex items-center">
            <div class="max-w-3xl text-white">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    {{ __('messages.find_dream_home') }}
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-200">
                    {{ __('messages.trusted_partner') }}
                </p>
                <!-- Quick Search Form (same as above) -->
                <div class="bg-white rounded-2xl shadow-2xl p-8">
                    <form action="{{ route('properties.search') }}" method="GET" 
                        x-data="{ 
                            selectedDistrict: '', 
                            omgevingen: [],
                            async loadOmgevingen() {
                                if (!this.selectedDistrict) {
                                    this.omgevingen = [];
                                    return;
                                }
                                try {
                                    const response = await fetch('/api/omgevingen?district_id=' + this.selectedDistrict);
                                    this.omgevingen = await response.json();
                                } catch (error) {
                                    console.error('Error:', error);
                                    this.omgevingen = [];
                                }
                            }
                        }">
                        <div class="grid grid-cols-1 gap-4" :class="omgevingen.length > 0 ? 'md:grid-cols-4' : 'md:grid-cols-3'">
                            <div>
                                <label for="object_type" class="block text-sm font-semibold text-gray-700 mb-3">{{ __('messages.type') }}</label>
                                <select name="object_type" id="object_type" class="w-full">
                                    <option value="">{{ __('messages.all_types') }}</option>
                                    @foreach($objectTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->naam }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="district_id" class="block text-sm font-semibold text-gray-700 mb-3">{{ __('messages.district') }}</label>
                                <select name="district_id" id="district_id" x-model="selectedDistrict" @change="loadOmgevingen()" class="w-full">
                                    <option value="">{{ __('messages.all_districts') }}</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->naam }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="omgevingen.length > 0" x-transition>
                                <label for="omgeving_id" class="block text-sm font-semibold text-gray-700 mb-3">{{ __('messages.area') }}</label>
                                <select name="omgeving_id" id="omgeving_id" class="w-full">
                                    <option value="">{{ __('messages.all_areas') }}</option>
                                    <template x-for="omgeving in omgevingen" :key="omgeving.id">
                                        <option :value="omgeving.id" x-text="omgeving.naam"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">&nbsp;</label>
                                <button type="submit" class="btn-accent w-full">
                                    <i class="fas fa-search mr-2"></i> {{ __('messages.search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Properties - Only show if 4 or more -->
    @if($featuredProperties->count() >= 4)
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">{{ __('messages.featured_properties') }}</h2>
                <p class="text-gray-600 text-lg">{{ __('messages.featured_properties_desc') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredProperties as $property)
                    <x-property-card :property="$property" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Recent Properties -->
    <section class="py-16 {{ $featuredProperties->count() >= 4 ? 'bg-gray-50' : '' }}">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">{{ __('messages.recent_properties') }}</h2>
                <p class="text-gray-600 text-lg">{{ __('messages.recent_properties_desc') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recentProperties as $property)
                    <x-property-card :property="$property" />
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('properties.sale') }}" class="inline-block px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-semibold">
                    {{ __('messages.view_all_properties') }} <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-12 bg-light">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $stats['total_properties'] }}+</div>
                    <div class="text-gray-600">{{ __('messages.properties') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $stats['properties_for_sale'] }}+</div>
                    <div class="text-gray-600">{{ __('messages.for_sale') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $stats['properties_for_rent'] }}+</div>
                    <div class="text-gray-600">{{ __('messages.for_rent') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $stats['happy_clients'] }}+</div>
                    <div class="text-gray-600">{{ __('messages.happy_clients') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">{{ __('messages.why_choose_us') }}</h2>
                <p class="text-gray-600 text-lg">{{ __('messages.trusted_partner') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">{{ __('messages.personal_service') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.personal_service_desc') }}
                    </p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">{{ __('messages.reliable') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.reliable_desc') }}
                    </p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">{{ __('messages.wide_range') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.wide_range_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('messages.ready_to_find') }}</h2>
            <p class="text-xl mb-8 text-gray-200">{{ __('messages.contact_today') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" class="px-8 py-3 bg-accent text-white rounded-lg hover:bg-opacity-90 transition font-semibold">
                    <i class="fas fa-envelope"></i> {{ __('messages.contact_us') }}
                </a>
                <a href="{{ route('properties.sale') }}" class="px-8 py-3 bg-white text-primary rounded-lg hover:bg-gray-100 transition font-semibold">
                    <i class="fas fa-search"></i> {{ __('messages.view_listings') }}
                </a>
            </div>
        </div>
    </section>
    
</x-app-layout>