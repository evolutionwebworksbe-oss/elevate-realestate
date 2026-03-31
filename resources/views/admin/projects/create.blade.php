<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Project</h2>
            <a href="{{ route('admin.projects.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>

    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <div class="py-6 px-6">

        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
            <i class="fas fa-circle-info mt-0.5 text-blue-500"></i>
            <div>
                <p class="font-medium">After saving you can add gallery images, YouTube videos and downloads.</p>
                <p class="text-sm text-blue-600 mt-0.5">Fill in the project details here first, then manage all media on the edit page.</p>
            </div>
        </div>
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" id="projectForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Main content -->
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Title</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title (Dutch) <span class="text-red-500">*</span></label>
                                <input type="text" name="title_nl" value="{{ old('title_nl') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                       required oninput="autoSlug(this.value)">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title (English)</label>
                                <input type="text" name="title_en" value="{{ old('title_en') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Short Description (excerpt)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dutch</label>
                                <textarea name="excerpt_nl" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                          placeholder="Brief summary shown on listing cards...">{{ old('excerpt_nl') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">English</label>
                                <textarea name="excerpt_en" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                          placeholder="Brief summary shown on listing cards...">{{ old('excerpt_en') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Full Description</h3>
                        <p class="text-sm text-gray-500 mb-3">Dutch</p>
                        <div id="editor_nl" style="min-height:250px">{{ old('description_nl') }}</div>
                        <input type="hidden" name="description_nl" id="description_nl">
                        <p class="text-sm text-gray-500 mt-6 mb-3">English</p>
                        <div id="editor_en" style="min-height:250px">{{ old('description_en') }}</div>
                        <input type="hidden" name="description_en" id="description_en">
                    </div>

                </div>

                <!-- Right: Sidebar -->
                <div class="space-y-6">

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Publish</h3>
                        <label class="flex items-center gap-2 mb-2">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', '1') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm">Published</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-yellow-500">
                            <span class="text-sm">Featured <i class="fas fa-star text-yellow-400 text-xs"></i></span>
                        </label>
                        <button type="submit"
                                class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Project
                        </button>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">URL / Slug</h3>
                        <div class="flex items-center gap-1">
                            <span class="text-gray-400 text-sm">/projecten/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md font-mono text-sm"
                                   placeholder="auto-generated">
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Details</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="project_type_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="">— No type —</option>
                                    @foreach($projectTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('project_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    @foreach(\App\Models\Project::$statuses as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', 'ongoing') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <input type="text" name="location" value="{{ old('location') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="e.g. Paramaribo">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Units</label>
                                    <input type="number" name="total_units" value="{{ old('total_units') }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Area (m²)</label>
                                    <input type="number" name="total_area" value="{{ old('total_area') }}" min="0" step="0.01"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Featured Image</h3>
                        <input type="file" name="featured_image" accept="image/*"
                               class="w-full text-sm text-gray-500">
                        <p class="text-xs text-gray-400 mt-1">Max 5MB. Shown as cover on listing.</p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">SEO</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        const toolbar = [[{header:[1,2,3,false]}],['bold','italic','underline','strike'],[{list:'ordered'},{list:'bullet'}],['link','blockquote'],[{color:[]},{background:[]}],['clean']];
        const quillNl = new Quill('#editor_nl', { theme:'snow', modules:{toolbar} });
        const quillEn = new Quill('#editor_en', { theme:'snow', modules:{toolbar} });

        document.getElementById('projectForm').addEventListener('submit', function() {
            document.getElementById('description_nl').value = quillNl.root.innerHTML;
            document.getElementById('description_en').value = quillEn.root.innerHTML;
        });

        let slugEdited = false;
        document.getElementById('slug').addEventListener('input', () => slugEdited = true);
        function autoSlug(v) {
            if (slugEdited) return;
            document.getElementById('slug').value = v.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
        }
    </script>
</x-admin-layout>
