<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Project Types</h2>
            <a href="{{ route('admin.settings.project-types.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Project Type
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name (NL)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name (EN)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Projects</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($projectTypes as $type)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $type->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $type->name_en ?: '—' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $type->projects_count }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.settings.project-types.edit', $type) }}"
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.settings.project-types.destroy', $type) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Delete this project type?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                No project types yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
