<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Object Bewerken: {{ $property->naam }}
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

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

            <!-- Featured Image Upload Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                        <i class="fas fa-image"></i> Uitgelichte Foto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Featured Image Display -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Huidige Uitgelichte Foto
                            </label>
                            <div id="featuredImagePreview" 
                                class="w-full h-64 bg-gray-200 rounded-lg bg-cover bg-center cursor-pointer hover:opacity-90 transition flex items-center justify-center"
                                onclick="document.getElementById('featuredImageInput').click()"
                                style="background-image: url('{{ $property->featuredFoto ? asset('portal/'.$property->featuredFoto) : asset('portal/img/geenfoto.jpg') }}'); min-height: 256px; background-size: cover; background-position: center;">
                                <div class="w-full h-full flex items-center justify-center bg-black bg-opacity-40 text-white opacity-0 hover:opacity-100 transition">
                                    <i class="fas fa-camera text-4xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Featured Image Upload Form -->
<!-- Featured Image Upload - Remove form and button -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Nieuwe Uitgelichte Foto
                            </label>
                            <input type="file" 
                                name="featuredImage" 
                                id="featuredImageInput"
                                accept="image/*"
                                class="hidden">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
                                onclick="document.getElementById('featuredImageInput').click()">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Klik om een foto te selecteren</p>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG of GIF (max 5MB)</p>
                                <p class="text-xs text-blue-500 mt-2">Foto wordt automatisch geüpload na selectie</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Gallery Images Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 flex-1">
                            <i class="fas fa-images"></i> Foto Galerij
                        </h3>
                        <p class="text-sm text-gray-600 italic">
                            <i class="fas fa-hand-pointer"></i> Sleep foto's om volgorde te wijzigen
                        </p>
                    </div>
                    
                    <!-- Upload Multiple Images -->
                    <div class="mb-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <input type="file" 
                                name="images[]" 
                                id="galleryImagesInput"
                                accept="image/*"
                                multiple
                                class="hidden">
                            <div class="cursor-pointer" onclick="document.getElementById('galleryImagesInput').click()">
                                <i class="fas fa-images text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Klik om meerdere foto's te selecteren</p>
                                <p class="text-xs text-gray-500 mt-1">U kunt meerdere bestanden tegelijk selecteren</p>
                                <p class="text-xs text-blue-500 mt-2">Foto's worden automatisch geüpload na selectie</p>
                            </div>
                        </div>
                    </div>

                    <!-- Display Existing Gallery Images -->
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="galleryImagesGrid">
                    @forelse($property->images as $image)
                        <div class="relative group cursor-move" data-image-id="{{ $image->id }}">
                            <div class="aspect-square bg-cover bg-center rounded-lg" 
                                style="background-image: url('{{ asset('portal/'.$image->url) }}')">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                    <i class="fas fa-grip-vertical text-white opacity-0 group-hover:opacity-100 text-2xl"></i>
                                </div>
                            </div>
                            <form action="{{ route('admin.properties.delete-image', [$property, $image]) }}" 
                                method="POST" 
                                class="absolute top-2 right-2"
                                onsubmit="return confirm('Weet u zeker dat u deze foto wilt verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            Geen galerij foto's beschikbaar
                        </div>
                    @endforelse
                    </div>

                    @if($property->images->count() > 0)
                        <form action="{{ route('admin.properties.delete-all-images', $property) }}" 
                            method="POST" 
                            class="mt-4"
                            onsubmit="return confirm('Weet u zeker dat u ALLE galerij foto\'s wilt verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-trash"></i> Verwijder Alle Galerij Foto's
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Property Information Form -->
            <form action="{{ route('admin.properties.update', $property) }}" method="POST">
                @csrf
                @method('PUT')

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
                                       value="{{ old('naam', $property->naam) }}"
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
                                        <option value="{{ $type->id }}" {{ old('objectType_id', $property->objectType_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->naam }}
                                        </option>
                                    @endforeach
                                </select>
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
                                                {{ old('objectSubType_id', $property->objectSubType_id) == $subType->id ? 'selected' : '' }}>
                                            {{ $subType->naam }}
                                        </option>
                                    @endforeach
                                </select>
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
                                    <option value="1" {{ old('status', $property->status) == '1' ? 'selected' : '' }}>Beschikbaar</option>
                                    <option value="2" {{ old('status', $property->status) == '2' ? 'selected' : '' }}>Verkocht/Verhuurd</option>
                                    <option value="3" {{ old('status', $property->status) == '3' ? 'selected' : '' }}>Gereserveerd</option>
                                </select>
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
                                    <option value="1" {{ old('corporate', $property->corporate) == '1' ? 'selected' : '' }}>Residentieel</option>
                                    <option value="2" {{ old('corporate', $property->corporate) == '2' ? 'selected' : '' }}>Zakelijk</option>
                                </select>
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
                                    <option value="0" {{ old('country', $property->country) == '0' ? 'selected' : '' }}>Suriname</option>
                                    <option value="1" {{ old('country', $property->country) == '1' ? 'selected' : '' }}>Curaçao</option>
                                </select>
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
                                        <option value="{{ $titel->id }}" {{ old('titel_id', $property->titel_id) == $titel->id ? 'selected' : '' }}>
                                            {{ $titel->naam }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Checkboxes -->
                            <div class="md:col-span-2 flex gap-6">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="byowner" 
                                           value="1"
                                           {{ old('byowner', $property->byowner) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Door Eigenaar</span>
                                </label>

                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="featured" 
                                           value="1"
                                           {{ old('featured', $property->featured) ? 'checked' : '' }}
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
                                        <option value="{{ $district->id }}" {{ old('district_id', $property->district_id) == $district->id ? 'selected' : '' }}>
                                            {{ $district->naam }}
                                        </option>
                                    @endforeach
                                </select>
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
                                                {{ old('omgeving_id', $property->omgeving_id) == $omgeving->id ? 'selected' : '' }}>
                                            {{ $omgeving->naam }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adres
                                </label>
                                <input type="text" 
                                       name="address" 
                                       id="address" 
                                       value="{{ old('address', $property->address) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Directions -->
                            <div class="md:col-span-2">
                                <label for="directions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Routebeschrijving (Google Maps Link)
                                </label>
                                <input type="text" 
                                       name="directions" 
                                       id="directions" 
                                       value="{{ old('directions', $property->directions) }}"
                                       placeholder="https://maps.google.com/..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                                       value="{{ old('vraagPrijs', $property->vraagPrijs) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
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
                                        <option value="{{ $currency->id }}" {{ old('currency', $property->currency) == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                       value="{{ old('discount', $property->discount) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                                       value="{{ old('aantalSlaapkamers', $property->aantalSlaapkamers) }}"
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
                                       value="{{ old('aantalBadkamers', $property->aantalBadkamers) }}"
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
                                       value="{{ old('toiletten_count', $property->details->toiletten_count ?? '') }}"
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
                                       value="{{ old('woonlagen', $property->details->woonlagen ?? '') }}"
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
                                       value="{{ old('woonkamer_count', $property->details->woonkamer_count ?? '') }}"
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
                                       value="{{ old('keuken_count', $property->details->keuken_count ?? '') }}"
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
                                       value="{{ old('woonOppervlakte', $property->woonOppervlakte) }}"
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
                                       value="{{ old('perceelOppervlakte', $property->perceelOppervlakte) }}"
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
                                    <option value="1" {{ old('oppervlakteEenheid', $property->oppervlakteEenheid) == '1' ? 'selected' : '' }}>M² (Vierkante Meter)</option>
                                    <option value="2" {{ old('oppervlakteEenheid', $property->oppervlakteEenheid) == '2' ? 'selected' : '' }}>Ha (Hectare)</option>
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
                                    <option value="1" {{ old('gemeubileerd', $property->gemeubileerd) == '1' ? 'selected' : '' }}>Ja</option>
                                    <option value="2" {{ old('gemeubileerd', $property->gemeubileerd) == '2' ? 'selected' : '' }}>Nee</option>
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
                                       value="{{ old('huurwaarborg', $property->huurwaarborg) }}"
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
                                       value="{{ old('beschikbaarheid', $property->beschikbaarheid) }}"
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
                                    <option value="open" {{ old('parkeergelegenheid_type', $property->details->parkeergelegenheid_type ?? '') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ old('parkeergelegenheid_type', $property->details->parkeergelegenheid_type ?? '') == 'closed' ? 'selected' : '' }}>Gesloten/Garage</option>
                                    <option value="both" {{ old('parkeergelegenheid_type', $property->details->parkeergelegenheid_type ?? '') == 'both' ? 'selected' : '' }}>Beide</option>
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
                                       value="{{ old('parkeerplaatsen_aantal', $property->details->parkeerplaatsen_aantal ?? '') }}"
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
                                           {{ old('airco_algemeen', $property->details->airco_algemeen ?? false) ? 'checked' : '' }}
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
                                       value="{{ old('airco_locaties', $property->details->airco_locaties ?? '') }}"
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
                                           {{ in_array($voorziening->id, old('voorzieningen', $property->voorzieningen->pluck('id')->toArray())) ? 'checked' : '' }}
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
                                           {{ in_array($beveiliging->id, old('beveiliging', $property->beveiliging->pluck('id')->toArray())) ? 'checked' : '' }}
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
                                           {{ in_array($ruimte->id, old('extra_ruimtes', $property->extraRuimtes->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $ruimte->naam }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Team Members -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-users"></i> Toegewezen Teamleden
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($teamMembers as $member)
                                <label class="flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                        name="team_members[]" 
                                        value="{{ $member->id }}"
                                        {{ in_array($member->id, old('team_members', $property->teamMembers->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 mt-1">
                                    <div class="ml-3 flex-1">
                                        <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->titleType->name ?? 'Geen titel' }}</div>
                                        @if($member->email)
                                            <div class="text-xs text-gray-400">{{ $member->email }}</div>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-full text-center text-gray-500 py-4">
                                    <p>Geen teamleden beschikbaar.</p>
                                    <a href="{{ route('admin.team.create') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                                        Voeg eerst teamleden toe
                                    </a>
                                </div>
                            @endforelse
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
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('omschrijving', $property->omschrijving) }}</textarea>
                            </div>

                            <!-- Description (English) -->
                            <div>
                                <label for="omschrijving_en" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description (English)
                                </label>
                                <textarea name="omschrijving_en" 
                                        id="omschrijving_en" 
                                        rows="6"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('omschrijving_en', $property->omschrijving_en) }}</textarea>
                            </div>

                            <!-- YouTube Video ID -->
                            <div>
                                <label for="youtube" class="block text-sm font-medium text-gray-700 mb-2">
                                    YouTube Video ID
                                </label>
                                <input type="text" 
                                       name="youtube" 
                                       id="youtube" 
                                       value="{{ old('youtube', $property->youtube) }}"
                                       placeholder="bijv., dQw4w9WgXcQ"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Alleen de video ID, niet de volledige URL</p>
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
                        <i class="fas fa-save"></i> Wijzigingen Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
<!-- Include SortableJS from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>


document.addEventListener('DOMContentLoaded', function() {
    const objectTypeSelect = document.getElementById('objectType_id');
    const subTypeSelect = document.getElementById('objectSubType_id');
    const districtSelect = document.getElementById('district_id');
    const omgevingSelect = document.getElementById('omgeving_id');
    
    // Initialize Sortable for gallery images
    const galleryGrid = document.getElementById('galleryImagesGrid');
    if (galleryGrid && galleryGrid.children.length > 0) {
        new Sortable(galleryGrid, {
            animation: 150,
            ghostClass: 'opacity-50',
            handle: '.cursor-move',
            onEnd: function(evt) {
                // Get the new order of images
                const imageOrder = [];
                galleryGrid.querySelectorAll('[data-image-id]').forEach(el => {
                    imageOrder.push(el.dataset.imageId);
                });
                
                // Send to server
                fetch('{{ route("admin.properties.reorder-images", $property) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: imageOrder })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                    } else {
                        showNotification('Fout bij opslaan volgorde', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Fout bij opslaan volgorde', 'error');
                });
            }
        });
    }
    
    // Auto-upload featured image when selected
    document.getElementById('featuredImageInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const formData = new FormData();
            formData.append('featuredImage', e.target.files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Show preview immediately
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('featuredImagePreview').style.backgroundImage = `url('${event.target.result}')`;
            }
            reader.readAsDataURL(e.target.files[0]);
            
            // Upload to server
            fetch('{{ route("admin.properties.upload-featured", $property) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                } else {
                    showNotification('Upload mislukt', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Upload mislukt', 'error');
            });
        }
    });

    // Auto-upload gallery images when selected
    document.getElementById('galleryImagesInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files.length > 0) {
            const formData = new FormData();
            
            for (let i = 0; i < e.target.files.length; i++) {
                formData.append('images[]', e.target.files[i]);
            }
            formData.append('_token', '{{ csrf_token() }}');
            
            showNotification(`Uploading ${e.target.files.length} foto(s)...`, 'info');
            
            fetch('{{ route("admin.properties.upload-gallery", $property) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    
                    // Add uploaded images to gallery grid
                    const grid = document.getElementById('galleryImagesGrid');
                    
                    // Remove "no images" message if exists
                    const emptyMessage = grid.querySelector('.col-span-full');
                    if (emptyMessage) emptyMessage.remove();
                    
                    data.images.forEach(image => {
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'relative group';
                        
                        // Extract just the path from the full URL and add portal prefix
                        const imageUrl = image.url.replace(window.location.origin, '');
                        const portalUrl = `${window.location.origin}/portal${imageUrl}`;
                        
                        imageDiv.innerHTML = `
                            <div class="aspect-square bg-cover bg-center rounded-lg" 
                                style="background-image: url('${portalUrl}')">
                            </div>
                            <form action="{{ url('admin/properties') }}/{{ $property->id }}/images/${image.id}" 
                                method="POST" 
                                class="absolute top-2 right-2 delete-form"
                                onsubmit="return confirm('Weet u zeker dat u deze foto wilt verwijderen?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        `;
                        grid.appendChild(imageDiv);
                    });
                    
                    // Clear file input
                    e.target.value = '';
                } else {
                    showNotification('Upload mislukt', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Upload mislukt', 'error');
            });
        }
    });
    
    // Notification helper
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        } text-white`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Field visibility logic
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

    async function translateDescription() {
    const dutchText = document.getElementById('omschrijving').value;
    const dutchName = document.getElementById('naam').value;
    
    if (!dutchText) {
        alert('Voer eerst een Nederlandse beschrijving in');
        return;
    }
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Vertalen...';
    
    try {
        const response = await fetch('{{ route("admin.properties.translate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                text: dutchText,
                name: dutchName 
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('omschrijving_en').value = data.translation;
            if (data.name_translation) {
                document.getElementById('naam_en').value = data.name_translation;
            }
            alert('Vertaling voltooid!');
        } else {
            alert('Vertaling mislukt: ' + (data.message || 'Onbekende fout'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Vertaling mislukt. Controleer de console voor details.');
    } finally {
        button.disabled = false;
        button.innerHTML = originalText;
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
