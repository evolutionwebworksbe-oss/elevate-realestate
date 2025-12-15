<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Slider
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-4xl mx-auto">
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-image"></i> Slider Information
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Title (Dutch) -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Titel (Nederlands) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       value="{{ old('title') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Title (English) -->
                            <div>
                                <label for="title_en" class="block text-sm font-medium text-gray-700 mb-2">
                                    Title (English)
                                </label>
                                <input type="text" 
                                       name="title_en" 
                                       id="title_en" 
                                       value="{{ old('title_en') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Description (Dutch) -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Beschrijving (Nederlands)
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                            </div>

                            <!-- Description (English) -->
                            <div>
                                <label for="description_en" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description (English)
                                </label>
                                <textarea name="description_en" 
                                          id="description_en" 
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description_en') }}</textarea>
                            </div>

                            <!-- Image -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Afbeelding <span class="text-red-500">*</span>
                                </label>
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       accept="image/*"
                                       class="w-full"
                                       required>
                                <p class="text-sm text-gray-500 mt-1">Recommended size: 1920x600px. Max 5MB.</p>
                            </div>

                            <!-- Order -->
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Volgorde <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="order" 
                                       id="order" 
                                       value="{{ old('order', 0) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
                            </div>

                            <!-- Active -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="active" 
                                           value="1" 
                                           {{ old('active', 1) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.sliders.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded">
                        <i class="fas fa-save"></i> Create Slider
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>