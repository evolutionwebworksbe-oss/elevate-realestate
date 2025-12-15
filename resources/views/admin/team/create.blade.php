<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Team Member
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

            <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-user"></i> Basic Information
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Slug (Auto-generated) -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL Slug <span class="text-gray-400">(auto-generated)</span>
                                </label>
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="slug" 
                                               id="slug" 
                                               value="{{ old('slug') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               placeholder="will-be-generated-from-name">
                                    </div>
                                    <button type="button" 
                                            id="regenerate-slug"
                                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                                        <i class="fas fa-sync-alt"></i> Regenerate
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Profile URL: <span class="font-mono text-blue-600" id="slug-preview">/team/will-be-generated</span>
                                </p>
                            </div>

                            <!-- Job Titles (Multiple Selection) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Job Titles <span class="text-red-500">*</span>
                                </label>
                                <div class="border rounded-md p-4 space-y-2 max-h-64 overflow-y-auto">
                                    @foreach($titleTypes as $titleType)
                                        <label class="flex items-center space-x-2 hover:bg-gray-50 p-2 rounded cursor-pointer">
                                            <input type="checkbox" 
                                                   name="titles[]" 
                                                   value="{{ $titleType->id }}"
                                                   {{ in_array($titleType->id, old('titles', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">{{ $titleType->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Select one or more titles for this team member</p>
                            </div>

                            <!-- Display Order -->
                            <div>
                                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Display Order
                                </label>
                                <input type="number" 
                                       name="display_order" 
                                       id="display_order" 
                                       value="{{ old('display_order', 0) }}"
                                       min="0"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (0 = default)</p>
                            </div>

                            <!-- Show as Agent (Exception Checkbox) -->
                            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded">
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="show_as_agent" 
                                           id="show_as_agent"
                                           value="1"
                                           {{ old('show_as_agent') ? 'checked' : '' }}
                                           class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-900">
                                            <i class="fas fa-star text-blue-600"></i> Show on Agents Page
                                        </span>
                                        <p class="text-xs text-gray-600 mt-1">
                                            Enable this to show this person on the "/makelaars" page even if they don't have the "Real Estate Agent" title.
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- WhatsApp -->
                            <div>
                                <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                    WhatsApp Number
                                </label>
                                <input type="text" 
                                    name="whatsapp" 
                                    id="whatsapp" 
                                    value="{{ old('whatsapp') }}"
                                    placeholder="+597 123 4567"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-camera"></i> Photo
                        </h3>
                        
                        <div class="flex items-start gap-6">
                            <!-- Preview -->
                            <div>
                                <div id="imagePreview" 
                                     class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                    <i class="fas fa-user text-6xl text-gray-400"></i>
                                </div>
                            </div>
                            
                            <!-- Upload -->
                            <div class="flex-1">
                                <input type="file" 
                                       name="image" 
                                       id="imageInput"
                                       accept="image/*"
                                       class="hidden">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition"
                                     onclick="document.getElementById('imageInput').click()">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click to select a photo</p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG or GIF (max 5MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.team.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded">
                        <i class="fas fa-save"></i> Save Team Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    // Slug generation function
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/[\s_-]+/g, '-')   // Replace spaces, underscores with hyphens
            .replace(/^-+|-+$/g, '');    // Remove leading/trailing hyphens
    }

    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const slugPreview = document.getElementById('slug-preview');
    const regenerateBtn = document.getElementById('regenerate-slug');

    // Update slug as user types name
    nameInput.addEventListener('input', function() {
        if (!slugInput.dataset.manuallyEdited) {
            const slug = generateSlug(this.value);
            slugInput.value = slug;
            slugPreview.textContent = slug ? `/team/${slug}` : '/team/will-be-generated';
        }
    });

    // Allow manual editing of slug
    slugInput.addEventListener('input', function() {
        slugInput.dataset.manuallyEdited = 'true';
        const slug = this.value;
        slugPreview.textContent = slug ? `/team/${slug}` : '/team/will-be-generated';
    });

    // Regenerate button
    regenerateBtn.addEventListener('click', function() {
        delete slugInput.dataset.manuallyEdited;
        const slug = generateSlug(nameInput.value);
        slugInput.value = slug;
        slugPreview.textContent = slug ? `/team/${slug}` : '/team/will-be-generated';
    });

    // Image preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('imagePreview').innerHTML = 
                    `<img src="${event.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    </script>
    @endpush
</x-admin-layout>
