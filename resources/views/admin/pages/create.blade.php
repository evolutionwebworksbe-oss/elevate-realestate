<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Page</h2>
            <a href="{{ route('admin.pages.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>

    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <div class="py-6 px-6">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pages.store') }}" method="POST" id="pageForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Titles -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Title</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title (Dutch) <span class="text-red-500">*</span></label>
                                <input type="text" name="title_nl" id="title_nl" value="{{ old('title_nl') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       required oninput="autoSlug(this.value)">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title (English)</label>
                                <input type="text" name="title_en" value="{{ old('title_en') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Content NL -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Content (Dutch)</h3>
                        <div id="editor_nl" style="min-height: 300px;">{{ old('content_nl') }}</div>
                        <input type="hidden" name="content_nl" id="content_nl">
                    </div>

                    <!-- Content EN -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Content (English)</h3>
                        <div id="editor_en" style="min-height: 300px;">{{ old('content_en') }}</div>
                        <input type="hidden" name="content_en" id="content_en">
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Publish -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Publish</h3>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_published" value="1"
                                   {{ old('is_published', '1') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm text-gray-700">Published (visible on site)</span>
                        </label>

                        <div class="mt-6 flex gap-3">
                            <button type="submit"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Page
                            </button>
                        </div>
                    </div>

                    <!-- Slug -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">URL / Slug</h3>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <div class="flex items-center gap-1">
                            <span class="text-gray-400 text-sm">/</span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="auto-generated">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Leave empty to auto-generate from title.</p>
                    </div>

                    <!-- SEO -->
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">SEO</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Defaults to page title">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Short description for search engines">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        const quillNl = new Quill('#editor_nl', { theme: 'snow', modules: { toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'blockquote'],
            [{ color: [] }, { background: [] }],
            ['clean']
        ]}});

        const quillEn = new Quill('#editor_en', { theme: 'snow', modules: { toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'blockquote'],
            [{ color: [] }, { background: [] }],
            ['clean']
        ]}});

        document.getElementById('pageForm').addEventListener('submit', function () {
            document.getElementById('content_nl').value = quillNl.root.innerHTML;
            document.getElementById('content_en').value = quillEn.root.innerHTML;
        });

        let slugManuallyEdited = false;
        document.getElementById('slug').addEventListener('input', () => { slugManuallyEdited = true; });

        function autoSlug(title) {
            if (slugManuallyEdited) return;
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
            document.getElementById('slug').value = slug;
        }
    </script>
</x-admin-layout>
