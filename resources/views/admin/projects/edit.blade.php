<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit: {{ $project->title_nl }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('projects.show', $project->slug) }}" target="_blank"
                   class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('admin.projects.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </x-slot>

    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <div class="py-6 px-6 space-y-6">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- ═══════════════════════════════════════ MAIN DETAILS ══ --}}
        <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" id="projectForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Title</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dutch <span class="text-red-500">*</span></label>
                                <input type="text" name="title_nl" value="{{ old('title_nl', $project->title_nl) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">English</label>
                                <input type="text" name="title_en" value="{{ old('title_en', $project->title_en) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Short Description</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dutch</label>
                                <textarea name="excerpt_nl" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('excerpt_nl', $project->excerpt_nl) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">English</label>
                                <textarea name="excerpt_en" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('excerpt_en', $project->excerpt_en) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Full Description</h3>
                        <p class="text-sm text-gray-500 mb-2">Dutch</p>
                        <div id="editor_nl" style="min-height:250px"></div>
                        <input type="hidden" name="description_nl" id="description_nl">
                        <p class="text-sm text-gray-500 mt-6 mb-2">English</p>
                        <div id="editor_en" style="min-height:250px"></div>
                        <input type="hidden" name="description_en" id="description_en">
                    </div>

                </div>

                <div class="space-y-6">

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Publish</h3>
                        <label class="flex items-center gap-2 mb-2">
                            <input type="checkbox" name="is_published" value="1"
                                   {{ old('is_published', $project->is_published) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm">Published</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-yellow-500">
                            <span class="text-sm">Featured <i class="fas fa-star text-yellow-400 text-xs"></i></span>
                        </label>
                        <button type="submit"
                                class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Save Changes
                        </button>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST"
                              class="mt-2" onsubmit="return confirm('Delete this project and all media?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded text-sm">
                                <i class="fas fa-trash mr-1"></i> Delete Project
                            </button>
                        </form>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">URL / Slug</h3>
                        <div class="flex items-center gap-1">
                            <span class="text-gray-400 text-sm">/projecten/</span>
                            <input type="text" name="slug" value="{{ old('slug', $project->slug) }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md font-mono text-sm" required>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Details</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="project_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="">— No type —</option>
                                    @foreach($projectTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('project_type_id', $project->project_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    @foreach(\App\Models\Project::$statuses as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $project->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <input type="text" name="location" value="{{ old('location', $project->location) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Units</label>
                                    <input type="number" name="total_units" value="{{ old('total_units', $project->total_units) }}" min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Area (m²)</label>
                                    <input type="number" name="total_area" value="{{ old('total_area', $project->total_area) }}" min="0" step="0.01"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Featured Image</h3>
                        @if($project->featured_image)
                            <img src="{{ asset('portal/projects/' . $project->featured_image) }}"
                                 class="w-full h-40 object-cover rounded mb-3">
                        @endif
                        <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-gray-500">
                        <p class="text-xs text-gray-400 mt-1">Max 5MB. Leave empty to keep current.</p>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">SEO</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $project->meta_title) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('meta_description', $project->meta_description) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        {{-- ═══════════════════════════════════════ GALLERY ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4"><i class="fas fa-images mr-2 text-blue-500"></i>Gallery</h3>

            {{-- Upload form --}}
            <form action="{{ route('admin.projects.upload-gallery', $project) }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <div class="flex items-center gap-3">
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="flex-1 text-sm text-gray-500 border border-gray-300 rounded-md px-3 py-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        <i class="fas fa-upload mr-1"></i> Upload
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Select multiple images at once. Max 5MB each.</p>
            </form>

            {{-- Gallery grid --}}
            @if($project->images->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3" id="galleryGrid">
                    @foreach($project->images as $image)
                    <div class="relative group" data-id="{{ $image->id }}">
                        <img src="{{ asset('portal/projects/gallery/' . $image->path) }}"
                             class="w-full h-24 object-cover rounded border border-gray-200">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition rounded flex items-center justify-center">
                            <form action="{{ route('admin.projects.delete-image', [$project, $image]) }}" method="POST"
                                  class="opacity-0 group-hover:opacity-100"
                                  onsubmit="return confirm('Delete image?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm text-center py-6">No gallery images yet.</p>
            @endif
        </div>

        {{-- ═══════════════════════════════════════ VIDEOS ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4"><i class="fab fa-youtube mr-2 text-red-500"></i>Videos</h3>

            <form action="{{ route('admin.projects.videos.store', $project) }}" method="POST" class="mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label>
                        <input type="url" name="url" placeholder="https://www.youtube.com/watch?v=..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title (optional)</label>
                        <input type="text" name="title" placeholder="Video title"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                <button type="submit" class="mt-3 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-plus mr-1"></i> Add Video
                </button>
            </form>

            @if($project->videos->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($project->videos as $video)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="relative" style="padding-top:56.25%">
                            <iframe src="{{ $video->getEmbedUrl() }}"
                                    class="absolute inset-0 w-full h-full"
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 truncate">{{ $video->title ?: 'Video ' . $loop->iteration }}</span>
                            <form action="{{ route('admin.projects.videos.destroy', [$project, $video]) }}" method="POST"
                                  onsubmit="return confirm('Remove video?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm ml-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm text-center py-6">No videos yet.</p>
            @endif
        </div>

        {{-- ═══════════════════════════════════════ DOWNLOADS ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4"><i class="fas fa-download mr-2 text-green-600"></i>Downloads</h3>

            <form action="{{ route('admin.projects.downloads.store', $project) }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label <span class="text-red-500">*</span></label>
                        <input type="text" name="label" placeholder="e.g. Brochure, Floor Plan"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                        <input type="file" name="file" class="w-full text-sm text-gray-500 border border-gray-300 rounded-md px-3 py-2">
                        <p class="text-xs text-gray-400 mt-1">Max 20MB. PDF, ZIP, etc.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Or External URL</label>
                        <input type="url" name="external_url" placeholder="https://..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                <button type="submit" class="mt-3 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-plus mr-1"></i> Add Download
                </button>
            </form>

            @if($project->downloads->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($project->downloads as $download)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-arrow-down text-green-600 text-xl"></i>
                            <div>
                                <p class="font-medium text-sm text-gray-800">{{ $download->label }}</p>
                                <a href="{{ $download->getDownloadUrl() }}" target="_blank"
                                   class="text-xs text-blue-500 hover:underline">
                                    {{ $download->file_path ? 'Uploaded file' : $download->external_url }}
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('admin.projects.downloads.destroy', [$project, $download]) }}" method="POST"
                              onsubmit="return confirm('Remove download?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm text-center py-6">No downloads yet.</p>
            @endif
        </div>

    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        const toolbar = [[{header:[1,2,3,false]}],['bold','italic','underline','strike'],[{list:'ordered'},{list:'bullet'}],['link','blockquote'],[{color:[]},{background:[]}],['clean']];
        const quillNl = new Quill('#editor_nl', { theme:'snow', modules:{toolbar} });
        const quillEn = new Quill('#editor_en', { theme:'snow', modules:{toolbar} });
        quillNl.root.innerHTML = {!! json_encode(old('description_nl', $project->description_nl)) !!};
        quillEn.root.innerHTML = {!! json_encode(old('description_en', $project->description_en)) !!};

        document.getElementById('projectForm').addEventListener('submit', function() {
            document.getElementById('description_nl').value = quillNl.root.innerHTML;
            document.getElementById('description_en').value = quillEn.root.innerHTML;
        });
    </script>
</x-admin-layout>
