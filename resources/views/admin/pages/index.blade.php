<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pages</h2>
            <a href="{{ route('admin.pages.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus"></i> Add Page
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title (NL)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title (EN)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug / URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pages as $page)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $page->title_nl }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $page->title_en ?: '—' }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                               class="text-blue-600 hover:underline font-mono text-sm">/{{ $page->slug }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $page->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $page->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this page? Menu items linking to it will be unlinked.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                            No pages yet. <a href="{{ route('admin.pages.create') }}" class="text-blue-600 hover:underline">Create your first page.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
