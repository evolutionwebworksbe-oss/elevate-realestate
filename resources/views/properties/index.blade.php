<x-app-layout>
    <x-slot name="title">{{ $title }} - Elevate Real Estate</x-slot>

    <div class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold text-dark mb-2">{{ $title }}</h1>
                <p class="text-gray-600">{{ $properties->total() }} {{ __('messages.properties_found') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Filter Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24" 
                        x-data="filterForm(
                            {{ $currentObjectType ? $currentObjectType->id : 'null' }},
                            '{{ request('district_id') }}',
                            '{{ request('omgeving_id') }}'
                        )">
                        <h2 class="text-xl font-bold text-dark mb-4">{{ __('messages.filters') }}</h2>
                        
                        <form method="GET" action="{{ url()->current() }}">
                            <!-- Object Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.type') }}</label>
                                <select name="object_type" x-model="objectType" @change="updateFilters()" class="w-full">
                                    <option value="">{{ __('messages.all_types') }}</option>
                                    @foreach($objectTypes as $type)
                                        @php
                                            $locale = app()->getLocale();
                                            $typeName = $type->naam;
                                            if ($locale == 'en') {
                                                $typeTranslations = [
                                                    'Te Koop' => 'For Sale',
                                                    'Te Huur' => 'For Rent',
                                                ];
                                                $typeName = $typeTranslations[$typeName] ?? $typeName;
                                            }
                                        @endphp
                                        <option value="{{ $type->id }}">{{ $typeName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Object SubType -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.subtype') }}</label>
                                <select name="object_subtype_id" class="w-full">
                                    <option value="">{{ __('messages.all_subtypes') }}</option>
                                    <template x-for="subtype in filteredSubtypes" :key="subtype.id">
                                        <option :value="subtype.id" 
                                                x-text="subtype.displayName"
                                                :selected="subtype.id == {{ $currentObjectSubType ? $currentObjectSubType->id : 'null' }}"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- District -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.district') }}</label>
                                <select name="district_id" x-model="district" @change="loadOmgevingen()" class="w-full">
                                    <option value="">{{ __('messages.all_districts') }}</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->naam }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Omgeving - Dynamic based on district -->
                            <div class="mb-4" x-show="district != ''">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.area') }}</label>
                                <select name="omgeving_id" class="w-full">
                                    <option value="">{{ __('messages.all_areas') }}</option>
                                    <template x-for="omgeving in omgevingen" :key="omgeving.id">
                                        <option :value="omgeving.id" 
                                                x-text="omgeving.naam"
                                                :selected="omgeving.id == {{ request('omgeving_id') ?? 'null' }}"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.price') }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" placeholder="{{ __('messages.min') }}" value="{{ request('min_price') }}" class="w-full">
                                    <input type="number" name="max_price" placeholder="{{ __('messages.max') }}" value="{{ request('max_price') }}" class="w-full">
                                </div>
                            </div>

                            <!-- Bedrooms -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.bedrooms') }}</label>
                                <select name="bedrooms" class="w-full">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+</option>
                                    <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+</option>
                                    <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+</option>
                                    <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+</option>
                                    <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5+</option>
                                </select>
                            </div>

                            <!-- Bathrooms -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.bathrooms') }}</label>
                                <select name="bathrooms" class="w-full">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="1" {{ request('bathrooms') == '1' ? 'selected' : '' }}>1+</option>
                                    <option value="2" {{ request('bathrooms') == '2' ? 'selected' : '' }}>2+</option>
                                    <option value="3" {{ request('bathrooms') == '3' ? 'selected' : '' }}>3+</option>
                                </select>
                            </div>

                            <!-- Surface Area -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.living_area') }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_surface" placeholder="{{ __('messages.min') }}" value="{{ request('min_surface') }}" class="w-full">
                                    <input type="number" name="max_surface" placeholder="{{ __('messages.max') }}" value="{{ request('max_surface') }}" class="w-full">
                                </div>
                            </div>

                            <!-- Titel - Only for "Te Koop" -->
                            <div class="mb-4" x-show="objectType == '2'">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.title') }}</label>
                                <select name="titel" class="w-full">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach(\App\Models\Titel::orderBy('naam')->get() as $titel)
                                        <option value="{{ $titel->id }}" {{ request('titel') == $titel->id ? 'selected' : '' }}>
                                            {{ $titel->naam }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.status') }}</label>
                                <select name="status" class="w-full">
                                    <option value="">{{ __('messages.all_statuses') }}</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('messages.available_only') }}</option>
                                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>{{ __('messages.sold_rented') }}</option>
                                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>{{ __('messages.reserved') }}</option>
                                </select>
                            </div>

                            <!-- Furnished -->
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="furnished" value="1" {{ request('furnished') ? 'checked' : '' }} class="mr-2">
                                    <span class="text-sm text-gray-700">{{ __('messages.furnished') }}</span>
                                </label>
                            </div>

                            <!-- Buttons -->
                            <div class="space-y-2">
                                <button type="submit" class="w-full btn-primary">
                                    <i class="fas fa-search mr-2"></i> {{ __('messages.search') }}
                                </button>
                                <a href="{{ url()->current() }}" class="w-full btn-secondary text-center block">
                                    <i class="fas fa-redo mr-2"></i> {{ __('messages.reset') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Properties Grid -->
                <div class="lg:col-span-3">
                    @if($properties->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($properties as $property)
                                <x-property-card :property="$property" />
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $properties->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl p-12 text-center">
                            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-bold text-dark mb-2">{{ __('messages.no_properties') }}</h3>
                            <p class="text-gray-600 mb-6">{{ __('messages.adjust_filters') }}</p>
                            <a href="{{ url()->current() }}" class="btn-primary inline-block">
                                {{ __('messages.reset_filters') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    // Translate subtypes on the client side
    const subtypeTranslations = {
        'nl': {},
        'en': {
            'Woningen': 'Houses',
            'Percelen': 'Lots',
            'Panden': 'Buildings',
            'Appartementen': 'Apartments',
            'Kantoren': 'Offices',
            'Bar/Restaurant': 'Bar/Restaurant',
            'Kantoor met werkloods': 'Office with Warehouse'
        }
    };
    
    const currentLocale = '{{ app()->getLocale() }}';
    
    // Add translated names to subtypes
    const subtypesWithTranslations = @json($objectSubTypes).map(subtype => ({
        ...subtype,
        displayName: currentLocale === 'en' && subtypeTranslations.en[subtype.naam] 
            ? subtypeTranslations.en[subtype.naam] 
            : subtype.naam
    }));

    function filterForm(preselectedType, preselectedDistrict, preselectedOmgeving) {
        return {
            objectType: preselectedType || '{{ request('object_type') }}',
            district: preselectedDistrict || '{{ request('district_id') }}',
            omgevingen: [],
            filteredSubtypes: subtypesWithTranslations,
            allSubtypes: subtypesWithTranslations,
            
            async loadOmgevingen() {
                if (!this.district) {
                    this.omgevingen = [];
                    return;
                }
                
                try {
                    const response = await fetch(`/api/omgevingen?district_id=${this.district}`);
                    this.omgevingen = await response.json();
                } catch (error) {
                    console.error('Error loading omgevingen:', error);
                }
            },
            
            updateFilters() {
                // Filter subtypes based on selected object type
                if (this.objectType) {
                    this.filteredSubtypes = this.allSubtypes.filter(subtype => 
                        subtype.objectType_id == this.objectType
                    );
                } else {
                    this.filteredSubtypes = this.allSubtypes;
                }
            },
            
            async init() {
                // Initialize filtered subtypes
                this.updateFilters();
                
                if (this.district) {
                    await this.loadOmgevingen();
                    
                    if (preselectedOmgeving) {
                        setTimeout(() => {
                            const omgevingSelect = document.querySelector('select[name="omgeving_id"]');
                            if (omgevingSelect) {
                                omgevingSelect.value = preselectedOmgeving;
                            }
                        }, 100);
                    }
                }
            }
        }
    }
</script>
@endpush
</x-app-layout>