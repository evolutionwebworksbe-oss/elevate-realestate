<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Slider
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-4xl mx-auto">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

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

            <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-image"></i> Slider Information
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Current Image Preview -->
                            @if($slider->image)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                    <img src="{{ asset('portal/' . $slider->image) }}" alt="{{ $slider->title }}" class="h-32 rounded-lg shadow">
                                </div>
                            @endif

                            <!-- Title (Dutch) -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Titel (Nederlands) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       value="{{ old('title', $slider->title) }}"
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
                                       value="{{ old('title_en', $slider->title_en) }}"
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
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $slider->description) }}</textarea>
                            </div>

                            <!-- Description (English) -->
                            <div>
                                <label for="description_en" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description (English)
                                </label>
                                <textarea name="description_en" 
                                          id="description_en" 
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description_en', $slider->description_en) }}</textarea>
                            </div>

                            <!-- Image -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nieuwe Afbeelding (optioneel)
                                </label>
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       accept="image/*"
                                       class="w-full">
                                <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image. Recommended size: 1920x600px. Max 5MB.</p>
                            </div>

                            <!-- Order -->
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Volgorde <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="order" 
                                       id="order" 
                                       value="{{ old('order', $slider->order) }}"
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
                                           {{ old('active', $slider->active) ? 'checked' : '' }}
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
                        <i class="fas fa-save"></i> Update Slider
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>