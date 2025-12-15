<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Instellingen
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Fout!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Image Optimization Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Afbeelding Optimalisatie</h3>
                            <p class="text-sm text-gray-600">Configureer hoe afbeeldingen worden geoptimaliseerd bij upload</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="image_optimization_enabled" 
                                   value="1"
                                   {{ old('image_optimization_enabled', $imageSettings->where('key', 'image_optimization_enabled')->first()->value ?? 1) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="space-y-4">
                        <!-- Max Width -->
                        <div>
                            <label for="image_max_width" class="block text-sm font-medium text-gray-700 mb-2">
                                Maximale Breedte (px)
                            </label>
                            <input type="number" 
                                   name="image_max_width" 
                                   id="image_max_width"
                                   value="{{ old('image_max_width', $imageSettings->where('key', 'image_max_width')->first()->value ?? 1920) }}"
                                   min="800" 
                                   max="4000"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('image_max_width')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Height -->
                        <div>
                            <label for="image_max_height" class="block text-sm font-medium text-gray-700 mb-2">
                                Maximale Hoogte (px)
                            </label>
                            <input type="number" 
                                   name="image_max_height" 
                                   id="image_max_height"
                                   value="{{ old('image_max_height', $imageSettings->where('key', 'image_max_height')->first()->value ?? 1080) }}"
                                   min="600" 
                                   max="3000"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('image_max_height')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Quality -->
                        <div>
                            <label for="image_quality" class="block text-sm font-medium text-gray-700 mb-2">
                                Afbeelding Kwaliteit (%)
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="range" 
                                       name="image_quality" 
                                       id="image_quality"
                                       value="{{ old('image_quality', $imageSettings->where('key', 'image_quality')->first()->value ?? 85) }}"
                                       min="50" 
                                       max="100"
                                       class="flex-1"
                                       oninput="document.getElementById('quality_display').textContent = this.value + '%'">
                                <span id="quality_display" class="text-sm font-medium text-gray-700 min-w-[3rem]">
                                    {{ old('image_quality', $imageSettings->where('key', 'image_quality')->first()->value ?? 85) }}%
                                </span>
                            </div>
                            @error('image_quality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Watermark Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Watermerk Instellingen</h3>
                            <p class="text-sm text-gray-600">Upload en configureer uw logo als watermerk</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="watermark_enabled" 
                                   value="1"
                                   {{ old('watermark_enabled', $watermarkSettings->where('key', 'watermark_enabled')->first()->value ?? 0) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Current Watermark Preview -->
                    @if($watermarkUrl)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Huidig Watermerk</label>
                            <div class="flex items-center gap-4">
                                <div class="bg-gray-100 p-4 rounded-lg inline-block">
                                    <img src="{{ $watermarkUrl }}" alt="Current watermark" class="max-h-32">
                                </div>
                                <button type="button" 
                                        onclick="if(confirm('Weet u zeker dat u het watermerk wilt verwijderen?')) { deleteWatermark(); }"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-trash"></i> Verwijderen
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <!-- Upload Watermark -->
                        <div>
                            <label for="watermark_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Nieuw Watermerk (PNG)
                            </label>
                            <input type="file" 
                                   name="watermark_file" 
                                   id="watermark_file"
                                   accept=".png"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Alleen PNG bestanden toegestaan. Max 2MB.</p>
                            @error('watermark_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Watermark Size -->
                        <div>
                            <label for="watermark_size" class="block text-sm font-medium text-gray-700 mb-2">
                                Watermerk Grootte (% van afbeelding)
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="range" 
                                       name="watermark_size" 
                                       id="watermark_size"
                                       value="{{ old('watermark_size', $watermarkSettings->where('key', 'watermark_size')->first()->value ?? 20) }}"
                                       min="10" 
                                       max="80"
                                       class="flex-1"
                                       oninput="document.getElementById('size_display').textContent = this.value + '%'">
                                <span id="size_display" class="text-sm font-medium text-gray-700 min-w-[3rem]">
                                    {{ old('watermark_size', $watermarkSettings->where('key', 'watermark_size')->first()->value ?? 20) }}%
                                </span>
                            </div>
                            @error('watermark_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Watermark Opacity -->
                        <div>
                            <label for="watermark_opacity" class="block text-sm font-medium text-gray-700 mb-2">
                                Watermerk Doorzichtigheid (%)
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="range" 
                                       name="watermark_opacity" 
                                       id="watermark_opacity"
                                       value="{{ old('watermark_opacity', $watermarkSettings->where('key', 'watermark_opacity')->first()->value ?? 50) }}"
                                       min="10" 
                                       max="100"
                                       class="flex-1"
                                       oninput="document.getElementById('opacity_display').textContent = this.value + '%'">
                                <span id="opacity_display" class="text-sm font-medium text-gray-700 min-w-[3rem]">
                                    {{ old('watermark_opacity', $watermarkSettings->where('key', 'watermark_opacity')->first()->value ?? 50) }}%
                                </span>
                            </div>
                            @error('watermark_opacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Watermark Position -->
                        <div>
                            <label for="watermark_position" class="block text-sm font-medium text-gray-700 mb-2">
                                Watermerk Positie
                            </label>
                            <select name="watermark_position" 
                                    id="watermark_position"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="center" {{ old('watermark_position', $watermarkSettings->where('key', 'watermark_position')->first()->value ?? 'center') == 'center' ? 'selected' : '' }}>Midden</option>
                                <option value="top-left" {{ old('watermark_position', $watermarkSettings->where('key', 'watermark_position')->first()->value ?? 'center') == 'top-left' ? 'selected' : '' }}>Linksboven</option>
                                <option value="top-right" {{ old('watermark_position', $watermarkSettings->where('key', 'watermark_position')->first()->value ?? 'center') == 'top-right' ? 'selected' : '' }}>Rechtsboven</option>
                                <option value="bottom-left" {{ old('watermark_position', $watermarkSettings->where('key', 'watermark_position')->first()->value ?? 'center') == 'bottom-left' ? 'selected' : '' }}>Linksonder</option>
                                <option value="bottom-right" {{ old('watermark_position', $watermarkSettings->where('key', 'watermark_position')->first()->value ?? 'center') == 'bottom-right' ? 'selected' : '' }}>Rechtsonder</option>
                            </select>
                            @error('watermark_position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Test Watermark -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Test Watermerk
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="file" 
                                       id="test_image" 
                                       accept="image/*"
                                       class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                <button type="button" 
                                        onclick="testWatermark()"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Test Watermerk
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Upload een test afbeelding om te zien hoe het watermerk eruit ziet</p>
                            <div id="test_result" class="mt-4"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding Settings (Logo & Favicon) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Logo & Favicon</h3>
                        <p class="text-sm text-gray-600">Upload en configureer uw website logo's en favicon</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Menu Logo Upload -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Menu Logo</h4>
                            
                            @if($logoMenuUrl)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Huidig Menu Logo</label>
                                    <div class="bg-gray-100 p-4 rounded-lg inline-block">
                                        <img src="{{ $logoMenuUrl }}" alt="Current menu logo" class="max-h-20">
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label for="logo_menu_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Nieuw Menu Logo
                                    </label>
                                    <input type="file" 
                                           name="logo_menu_file" 
                                           id="logo_menu_file"
                                           accept=".png,.jpg,.jpeg,.svg"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG of SVG. Max 5MB.</p>
                                    @error('logo_menu_file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="logo_menu_max_width" class="block text-sm font-medium text-gray-700 mb-2">
                                        Max Breedte (px)
                                    </label>
                                    <input type="number" 
                                           name="logo_menu_max_width" 
                                           id="logo_menu_max_width"
                                           value="{{ old('logo_menu_max_width', $brandingSettings->where('key', 'logo_menu_max_width')->first()->value ?? 200) }}"
                                           min="50" 
                                           max="500"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="logo_menu_max_height" class="block text-sm font-medium text-gray-700 mb-2">
                                        Max Hoogte (px)
                                    </label>
                                    <input type="number" 
                                           name="logo_menu_max_height" 
                                           id="logo_menu_max_height"
                                           value="{{ old('logo_menu_max_height', $brandingSettings->where('key', 'logo_menu_max_height')->first()->value ?? 80) }}"
                                           min="20" 
                                           max="200"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Logo Upload -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Footer Logo</h4>
                            
                            @if($logoFooterUrl)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Huidig Footer Logo</label>
                                    <div class="bg-gray-100 p-4 rounded-lg inline-block">
                                        <img src="{{ $logoFooterUrl }}" alt="Current footer logo" class="max-h-16">
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label for="logo_footer_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Nieuw Footer Logo
                                    </label>
                                    <input type="file" 
                                           name="logo_footer_file" 
                                           id="logo_footer_file"
                                           accept=".png,.jpg,.jpeg,.svg"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG of SVG. Max 5MB.</p>
                                    @error('logo_footer_file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="logo_footer_max_width" class="block text-sm font-medium text-gray-700 mb-2">
                                        Max Breedte (px)
                                    </label>
                                    <input type="number" 
                                           name="logo_footer_max_width" 
                                           id="logo_footer_max_width"
                                           value="{{ old('logo_footer_max_width', $brandingSettings->where('key', 'logo_footer_max_width')->first()->value ?? 150) }}"
                                           min="50" 
                                           max="500"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="logo_footer_max_height" class="block text-sm font-medium text-gray-700 mb-2">
                                        Max Hoogte (px)
                                    </label>
                                    <input type="number" 
                                           name="logo_footer_max_height" 
                                           id="logo_footer_max_height"
                                           value="{{ old('logo_footer_max_height', $brandingSettings->where('key', 'logo_footer_max_height')->first()->value ?? 60) }}"
                                           min="20" 
                                           max="200"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Favicon Upload -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Favicon</h4>
                            
                            @if($faviconUrl)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Huidige Favicon</label>
                                    <div class="bg-gray-100 p-4 rounded-lg inline-block">
                                        <img src="{{ $faviconUrl }}" alt="Current favicon" class="w-8 h-8">
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label for="favicon_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Nieuwe Favicon
                                </label>
                                <input type="file" 
                                       name="favicon_file" 
                                       id="favicon_file"
                                       accept=".png,.ico"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">PNG of ICO. Max 1MB. Wordt automatisch aangepast naar 32x32px.</p>
                                @error('favicon_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Instellingen Opslaan</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function deleteWatermark() {
            fetch('{{ route('admin.settings.watermark.delete') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Fout bij verwijderen watermerk');
                }
            })
            .catch(error => {
                alert('Fout bij verwijderen watermerk');
            });
        }
        
        function testWatermark() {
            const fileInput = document.getElementById('test_image');
            const resultDiv = document.getElementById('test_result');
            
            if (!fileInput.files.length) {
                alert('Selecteer eerst een test afbeelding');
                return;
            }
            
            const formData = new FormData();
            formData.append('test_image', fileInput.files[0]);
            
            resultDiv.innerHTML = '<p class="text-gray-600">Bezig met verwerken...</p>';
            
            fetch('{{ route('admin.settings.watermark.test') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <p class="mb-2">${data.message}</p>
                            <img src="${data.url}" alt="Test result" class="max-w-md rounded shadow-lg">
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        Er is een fout opgetreden bij het testen
                    </div>
                `;
            });
        }
    </script>
</x-admin-layout>
