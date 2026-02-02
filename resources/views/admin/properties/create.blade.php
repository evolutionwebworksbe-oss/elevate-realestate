<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nieuw Object Toevoegen
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="max-w-7xl mx-auto">
                
                <!-- Error Summary -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>Los de volgende fouten op:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-info-circle"></i> Basis Informatie
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Property Name -->
                            <div class="md:col-span-2">
                                <label for="naam" class="block text-sm font-medium text-gray-700 mb-2">
                                    Naam Object <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="naam" 
                                       id="naam" 
                                       value="{{ old('naam') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('naam')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Object Type -->
                            <div>
                                <label for="objectType_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Object Type <span class="text-red-500">*</span>
                                </label>
                                <select name="objectType_id" 
                                        id="objectType_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="">Selecteer Type</option>
                                    @foreach($objectTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('objectType_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->naam }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('objectType_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Object SubType -->
                            <div>
                                <label for="objectSubType_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Object SubType <span class="text-red-500">*</span>
                                </label>
                                <select name="objectSubType_id" 
                                        id="objectSubType_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="">Selecteer SubType</option>
                                    @foreach($objectSubTypes as $subType)
                                        <option value="{{ $subType->id }}" 
                                                data-type="{{ $subType->objectType_id }}"
                                                {{ old('objectSubType_id') == $subType->id ? 'selected' : '' }}>
                                            {{ $subType->naam }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('objectSubType_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" 
                                        id="status" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Beschikbaar</option>
                                    <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Verkocht/Verhuurd</option>
                                    <option value="3" {{ old('status') == '3' ? 'selected' : '' }}>Gereserveerd</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Corporate -->
                            <div>
                                <label for="corporate" class="block text-sm font-medium text-gray-700 mb-2">
                                    Categorie <span class="text-red-500">*</span>
                                </label>
                                <select name="corporate" 
                                        id="corporate" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="1" {{ old('corporate') == '1' ? 'selected' : '' }}>Residentieel</option>
                                    <option value="2" {{ old('corporate') == '2' ? 'selected' : '' }}>Zakelijk</option>
                                </select>
                                @error('corporate')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                    Land <span class="text-red-500">*</span>
                                </label>
                                <select name="country" 
                                        id="country" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="0" {{ old('country') == '0' ? 'selected' : '' }}>Suriname</option>
                                    <option value="1" {{ old('country') == '1' ? 'selected' : '' }}>Curaçao</option>
                                </select>
                                @error('country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Titel -->
                            <div>
                                <label for="titel_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Titel
                                </label>
                                <select name="titel_id" 
                                        id="titel_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecteer Titel</option>
                                    @foreach($titels as $titel)
                                        <option value="{{ $titel->id }}" {{ old('titel_id') == $titel->id ? 'selected' : '' }}>
                                            {{ $titel->naam }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('titel_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Checkboxes -->
                            <div class="md:col-span-2 flex gap-6">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="byowner" 
                                           value="1"
                                           {{ old('byowner') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Door Eigenaar</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="featured" 
                                           value="1"
                                           {{ old('featured') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Uitgelicht</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-map-marker-alt"></i> Locatie
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- District -->
                            <div>
                                <label for="district_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    District <span class="text-red-500">*</span>
                                </label>
                                <select name="district_id" 
                                        id="district_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="">Selecteer District</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->naam }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('district_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Omgeving -->
                            <div>
                                <label for="omgeving_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Omgeving
                                </label>
                                <select name="omgeving_id" 
                                        id="omgeving_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecteer Omgeving</option>
                                    @foreach($omgevingen as $omgeving)
                                        <option value="{{ $omgeving->id }}" 
                                                data-district="{{ $omgeving->district_id }}"
                                                {{ old('omgeving_id') == $omgeving->id ? 'selected' : '' }}>
                                            {{ $omgeving->naam }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('omgeving_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adres
                                </label>
                                <input type="text" 
                                       name="address" 
                                       id="address" 
                                       value="{{ old('address') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Directions -->
                            <div class="md:col-span-2">
                                <label for="directions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Routebeschrijving (Google Maps Link)
                                </label>
                                <input type="text" 
                                       name="directions" 
                                       id="directions" 
                                       value="{{ old('directions') }}"
                                       placeholder="https://maps.google.com/..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('directions')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-dollar-sign"></i> Prijzen
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Price -->
                            <div>
                                <label for="vraagPrijs" class="block text-sm font-medium text-gray-700 mb-2">
                                    Vraagprijs <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="vraagPrijs" 
                                       id="vraagPrijs" 
                                       step="0.01"
                                       value="{{ old('vraagPrijs') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('vraagPrijs')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Currency -->
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                                    Valuta <span class="text-red-500">*</span>
                                </label>
                                <select name="currency" 
                                        id="currency" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="">Selecteer Valuta</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency') == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount Price -->
                            <div>
                                <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kortingsprijs
                                </label>
                                <input type="number" 
                                       name="discount" 
                                       id="discount" 
                                       step="0.01"
                                       value="{{ old('discount') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('discount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-home"></i> Object Details
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Bedrooms -->
                            <div>
                                <label for="aantalSlaapkamers" class="block text-sm font-medium text-gray-700 mb-2">
                                    Slaapkamers
                                </label>
                                <input type="number" 
                                       name="aantalSlaapkamers" 
                                       id="aantalSlaapkamers" 
                                       value="{{ old('aantalSlaapkamers') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Bathrooms -->
                            <div>
                                <label for="aantalBadkamers" class="block text-sm font-medium text-gray-700 mb-2">
                                    Badkamers
                                </label>
                                <input type="number" 
                                       name="aantalBadkamers" 
                                       id="aantalBadkamers" 
                                       value="{{ old('aantalBadkamers') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Toilets -->
                            <div>
                                <label for="toiletten_count" class="block text-sm font-medium text-gray-700 mb-2">
                                    Toiletten
                                </label>
                                <input type="number" 
                                       name="toiletten_count" 
                                       id="toiletten_count" 
                                       value="{{ old('toiletten_count') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Floors -->
                            <div>
                                <label for="woonlagen" class="block text-sm font-medium text-gray-700 mb-2">
                                    Woonlagen
                                </label>
                                <input type="number" 
                                       name="woonlagen" 
                                       id="woonlagen" 
                                       value="{{ old('woonlagen') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Living Rooms -->
                            <div>
                                <label for="woonkamer_count" class="block text-sm font-medium text-gray-700 mb-2">
                                    Woonkamers
                                </label>
                                <input type="number" 
                                       name="woonkamer_count" 
                                       id="woonkamer_count" 
                                       value="{{ old('woonkamer_count') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Kitchens -->
                            <div>
                                <label for="keuken_count" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keukens
                                </label>
                                <input type="number" 
                                       name="keuken_count" 
                                       id="keuken_count" 
                                       value="{{ old('keuken_count') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Living Area -->
                            <div>
                                <label for="woonOppervlakte" class="block text-sm font-medium text-gray-700 mb-2">
                                    Woonoppervlakte (m²)
                                </label>
                                <input type="number" 
                                       name="woonOppervlakte" 
                                       id="woonOppervlakte" 
                                       step="0.01"
                                       value="{{ old('woonOppervlakte') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Land Area -->
                            <div>
                                <label for="perceelOppervlakte" class="block text-sm font-medium text-gray-700 mb-2">
                                    Perceeloppervlakte
                                </label>
                                <input type="number" 
                                       name="perceelOppervlakte" 
                                       id="perceelOppervlakte" 
                                       step="0.01"
                                       value="{{ old('perceelOppervlakte') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Area Unit -->
                            <div class="lg:col-span-2">
                                <label for="oppervlakteEenheid" class="block text-sm font-medium text-gray-700 mb-2">
                                    Oppervlakte Eenheid
                                </label>
                                <select name="oppervlakteEenheid" 
                                        id="oppervlakteEenheid" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecteer Eenheid</option>
                                    <option value="1" {{ old('oppervlakteEenheid') == '1' ? 'selected' : '' }}>M² (Vierkante Meter)</option>
                                    <option value="2" {{ old('oppervlakteEenheid') == '2' ? 'selected' : '' }}>Ha (Hectare)</option>
                                </select>
                            </div>

                            <!-- Furnished -->
                            <div>
                                <label for="gemeubileerd" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gemeubileerd
                                </label>
                                <select name="gemeubileerd" 
                                        id="gemeubileerd" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecteer</option>
                                    <option value="1" {{ old('gemeubileerd') == '1' ? 'selected' : '' }}>Ja</option>
                                    <option value="2" {{ old('gemeubileerd') == '2' ? 'selected' : '' }}>Nee</option>
                                </select>
                            </div>

                            <!-- Rental Deposit -->
                            <div class="lg:col-span-2">
                                <label for="huurwaarborg" class="block text-sm font-medium text-gray-700 mb-2">
                                    Huurwaarborg
                                </label>
                                <input type="text" 
                                       name="huurwaarborg" 
                                       id="huurwaarborg" 
                                       value="{{ old('huurwaarborg') }}"
                                       placeholder="bijv., 1 maand borg"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Availability -->
                            <div class="lg:col-span-2">
                                <label for="beschikbaarheid" class="block text-sm font-medium text-gray-700 mb-2">
                                    Beschikbaarheid
                                </label>
                                <input type="text" 
                                       name="beschikbaarheid" 
                                       id="beschikbaarheid" 
                                       value="{{ old('beschikbaarheid') }}"
                                       placeholder="bijv., Direct beschikbaar, 1 januari 2025"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parking & Airco -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-car"></i> Parkeren & Klimaatbeheersing
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Parking Type -->
                            <div>
                                <label for="parkeergelegenheid_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Parkeertype
                                </label>
                                <select name="parkeergelegenheid_type" 
                                        id="parkeergelegenheid_type" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecteer Type</option>
                                    <option value="open" {{ old('parkeergelegenheid_type') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ old('parkeergelegenheid_type') == 'closed' ? 'selected' : '' }}>Gesloten/Garage</option>
                                    <option value="both" {{ old('parkeergelegenheid_type') == 'both' ? 'selected' : '' }}>Beide</option>
                                </select>
                            </div>

                            <!-- Number of Parking Spots -->
                            <div>
                                <label for="parkeerplaatsen_aantal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Aantal Parkeerplaatsen
                                </label>
                                <input type="number" 
                                       name="parkeerplaatsen_aantal" 
                                       id="parkeerplaatsen_aantal" 
                                       value="{{ old('parkeerplaatsen_aantal') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Airco -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Airconditioning
                                </label>
                                <label class="flex items-center mt-2">
                                    <input type="checkbox" 
                                           name="airco_algemeen" 
                                           value="1"
                                           {{ old('airco_algemeen') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Heeft Airconditioning</span>
                                </label>
                            </div>

                            <!-- Airco Locations -->
                            <div class="md:col-span-3">
                                <label for="airco_locaties" class="block text-sm font-medium text-gray-700 mb-2">
                                    Airco Locaties (komma gescheiden)
                                </label>
                                <input type="text" 
                                       name="airco_locaties" 
                                       id="airco_locaties" 
                                       value="{{ old('airco_locaties') }}"
                                       placeholder="bijv., Woonkamer, Slaapkamer 1, Slaapkamer 2"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facilities (Voorzieningen) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-plug"></i> Voorzieningen
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($voorzieningen as $voorziening)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="voorzieningen[]" 
                                           value="{{ $voorziening->id }}"
                                           {{ in_array($voorziening->id, old('voorzieningen', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $voorziening->naam }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Security (Beveiliging) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-shield-alt"></i> Beveiliging
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($beveiligingTypes as $beveiliging)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="beveiliging[]" 
                                           value="{{ $beveiliging->id }}"
                                           {{ in_array($beveiliging->id, old('beveiliging', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $beveiliging->naam }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Extra Spaces (Extra Ruimtes) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-plus-circle"></i> Extra Ruimtes
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($extraRuimteTypes as $ruimte)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="extra_ruimtes[]" 
                                           value="{{ $ruimte->id }}"
                                           {{ in_array($ruimte->id, old('extra_ruimtes', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $ruimte->naam }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Description & Media -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-file-alt"></i> Omschrijving & Media
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Description (Dutch) -->
                            <div>
                                <label for="omschrijving" class="block text-sm font-medium text-gray-700 mb-2">
                                    Omschrijving (Nederlands)
                                </label>
                                <textarea name="omschrijving" 
                                          id="omschrijving" 
                                          rows="6"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('omschrijving') }}</textarea>
                                @error('omschrijving')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description (English) -->
                            <div>
                                <label for="omschrijving_en" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description (English)
                                </label>
                                <textarea name="omschrijving_en" 
                                          id="omschrijving_en" 
                                          rows="6"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('omschrijving_en') }}</textarea>
                                @error('omschrijving_en')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- YouTube Video ID -->
                            <div>
                                <label for="youtube" class="block text-sm font-medium text-gray-700 mb-2">
                                    YouTube Video ID
                                </label>
                                <input type="text" 
                                       name="youtube" 
                                       id="youtube" 
                                       value="{{ old('youtube') }}"
                                       placeholder="bijv., dQw4w9WgXcQ"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Alleen de video ID, niet de volledige URL</p>
                                @error('youtube')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.properties.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded">
                        Annuleren
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded">
                        <i class="fas fa-save"></i> Object Aanmaken
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const objectTypeSelect = document.getElementById('objectType_id');
        const subTypeSelect = document.getElementById('objectSubType_id');
        const districtSelect = document.getElementById('district_id');
        const omgevingSelect = document.getElementById('omgeving_id');
        
        function getFieldContainer(inputName) {
            const input = document.querySelector(`[name="${inputName}"]`);
            if (!input) return null;
            
            let parent = input.parentElement;
            while (parent && !parent.className.includes('col-span')) {
                parent = parent.parentElement;
            }
            return parent || input.parentElement;
        }
        
        const fields = {
            woonOppervlakte: getFieldContainer('woonOppervlakte'),
            aantalSlaapkamers: getFieldContainer('aantalSlaapkamers'),
            aantalBadkamers: getFieldContainer('aantalBadkamers'),
            gemeubileerd: getFieldContainer('gemeubileerd'),
            huurwaarborg: getFieldContainer('huurwaarborg'),
            beschikbaarheid: getFieldContainer('beschikbaarheid'),
            titel_id: getFieldContainer('titel_id')
        };

        function filterSubTypes() {
            const selectedType = objectTypeSelect.value;
            const subTypeOptions = subTypeSelect.querySelectorAll('option');
            
            subTypeOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }
                option.style.display = option.dataset.type === selectedType ? 'block' : 'none';
            });
            
            const currentOption = subTypeSelect.querySelector(`option[value="${subTypeSelect.value}"]`);
            if (currentOption && currentOption.style.display === 'none') {
                subTypeSelect.value = '';
            }
        }

        function updateFieldVisibility() {
            const subTypeText = subTypeSelect.options[subTypeSelect.selectedIndex]?.text.toLowerCase() || '';
            const typeText = objectTypeSelect.options[objectTypeSelect.selectedIndex]?.text.toLowerCase() || '';
            
            Object.values(fields).forEach(field => {
                if (field) field.style.display = '';
            });

            if (subTypeText.includes('percelen') || subTypeText.includes('perceel')) {
                ['woonOppervlakte', 'aantalSlaapkamers', 'aantalBadkamers', 'gemeubileerd'].forEach(key => {
                    if (fields[key]) fields[key].style.display = 'none';
                });
            }
            
            if (typeText.includes('huur')) {
                if (fields.titel_id) fields.titel_id.style.display = 'none';
            } else if (typeText.includes('koop')) {
                if (fields.huurwaarborg) fields.huurwaarborg.style.display = 'none';
                if (fields.beschikbaarheid) fields.beschikbaarheid.style.display = 'none';
            }
        }

        function filterOmgevingen() {
            const selectedDistrict = districtSelect.value;
            const options = omgevingSelect.querySelectorAll('option');
            let hasVisible = false;
            
            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }
                
                if (option.dataset.district === selectedDistrict) {
                    option.style.display = 'block';
                    hasVisible = true;
                } else {
                    option.style.display = 'none';
                }
            });
            
            const omgevingField = omgevingSelect.parentElement;
            if (omgevingField) {
                omgevingField.style.display = hasVisible ? '' : 'none';
            }
            
            const currentOption = omgevingSelect.querySelector(`option[value="${omgevingSelect.value}"]`);
            if (currentOption && currentOption.style.display === 'none') {
                omgevingSelect.value = '';
            }
        }

        objectTypeSelect.addEventListener('change', () => {
            filterSubTypes();
            updateFieldVisibility();
        });
        
        subTypeSelect.addEventListener('change', updateFieldVisibility);
        districtSelect.addEventListener('change', filterOmgevingen);

        filterSubTypes();
        updateFieldVisibility();
        filterOmgevingen();
    });
    </script>
    @endpush
</x-admin-layout>
