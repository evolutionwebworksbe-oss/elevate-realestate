@props(['property'])

@php
    $locale = app()->getLocale();
    $name = $locale == 'en' && $property->naam_en ? $property->naam_en : $property->naam;
    
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
    
    // Get status info
    $hasStatusBadge = in_array($property->status, [2, 3]);
    $isSale = $property->objectType && str_contains(strtolower($property->objectType->naam), 'koop');
@endphp

<div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group h-full flex flex-col">
    <a href="{{ route('properties.show', $property) }}" class="flex flex-col flex-1">
        <!-- Image -->
        <div class="relative h-72 overflow-hidden rounded-b-3xl">
            @if($property->featuredFoto)
                <img src="{{ asset('portal' . $property->featuredFoto) }}" 
                    alt="{{ $name }}"
                    class="w-full h-full object-cover transition duration-500">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-home text-5xl text-gray-400"></i>
                </div>
            @endif
            
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition duration-300"></div>

            <!-- Diagonal Status Ribbon (Verhuurd/Verkocht/Gereserveerd) - Full Diagonal -->
            @if($property->status == 2)
                @php
                    $statusLabel = $isSale ? __('messages.sold') : __('messages.rented');
                @endphp
                <div class="absolute inset-0 pointer-events-none overflow-hidden z-[5]">
                    <div class="absolute top-1/2 left-1/2 w-[200%] bg-[#34637ea1] text-white text-center py-2 text-sm font-bold tracking-widest transform -translate-x-1/2 -translate-y-1/2 rotate-45 shadow-2xl">
                        {{ strtoupper($statusLabel) }}
                    </div>
                </div>
            @elseif($property->status == 3)
                <div class="absolute inset-0 pointer-events-none overflow-hidden z-[5]">
                    <div class="absolute top-1/2 left-1/2 w-[200%] bg-[#ce4e4eab] text-white text-center py-2 text-sm font-bold tracking-widest transform -translate-x-1/2 -translate-y-1/2 rotate-45 shadow-2xl">
                        {{ strtoupper(__('messages.reserved')) }}
                    </div>
                </div>
            @endif

            <!-- Top Left Badge - Object SubType -->
            @if($property->objectSubType)
                <div class="absolute top-4 left-4 z-20">
                    <span class="px-4 py-2 bg-white bg-opacity-95 text-gray-800 text-xs font-bold rounded-full shadow-lg">
                        {{ strtoupper($objectSubTypeName) }}
                    </span>
                </div>
            @endif

            <!-- Status/Special Badges - Top Right -->
            <div class="absolute top-4 right-4 flex flex-col gap-2 items-end z-20">
                @if($property->objectType)
                    <span class="px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-lg shadow-lg w-28 text-center">
                        {{ strtoupper($objectTypeName) }}
                    </span>
                @endif     

                @if($property->corporate == 2)
                    <span class="px-3 py-1.5 bg-[#A4CE4E] text-white text-xs font-bold rounded-full shadow-lg w-28 text-center">
                        {{ strtoupper(__('messages.corporate')) }}
                    </span>
                @endif
                
                @if($property->byowner)
                    <span class="px-3 py-1.5 bg-purple-600 text-white text-xs font-bold rounded-full shadow-lg w-28 text-center">
                        {{ strtoupper(__('messages.owner')) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Title and Price Row -->
            <div class="flex justify-between items-start mb-3">
                <!-- Title - Left -->
                <h3 class="text-xl font-bold text-dark flex-1 pr-4 line-clamp-2">
                    {{ $name }}
                </h3>
                
                <!-- Price - Right -->
                <div class="text-right flex-shrink-0">
                    @if($property->discount && $property->discount > 0 && $property->discount < $property->vraagPrijs)
                        <div class="text-2xl font-bold text-dark">
                            ${{ number_format($property->discount, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-400 line-through">
                            ${{ number_format($property->vraagPrijs, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-2xl font-bold text-dark">
                            ${{ number_format($property->vraagPrijs, 0, ',', '.') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Location -->
            <p class="text-gray-600 text-sm mb-4 flex items-center">
                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                {{ $property->district->naam ?? '' }}
            </p>

            <!-- Features -->
            <div class="flex items-center gap-6 text-sm text-gray-600 pt-4 border-t border-gray-100">
                @if($property->aantalSlaapkamers)
                    <span class="flex items-center gap-2">
                        <i class="fas fa-bed text-gray-400"></i>
                        <span>{{ $property->aantalSlaapkamers }}</span>
                    </span>
                @endif
                @if($property->aantalBadkamers)
                    <span class="flex items-center gap-2">
                        <i class="fas fa-bath text-gray-400"></i>
                        <span>{{ $property->aantalBadkamers }}</span>
                    </span>
                @endif
                @if($property->woonOppervlakte)
                    <span class="flex items-center gap-2">
                        <i class="fas fa-ruler-combined text-gray-400"></i>
                        <span>{{ $property->woonOppervlakte }} m²</span>
                    </span>
                @endif
            </div>
        </div>
    </a>
</div>
