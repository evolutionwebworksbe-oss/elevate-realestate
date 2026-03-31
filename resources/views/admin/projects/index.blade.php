<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
            <a href="{{ route('admin.projects.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus"></i> Add Project
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-16">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Published</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Featured</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($project->featured_image)
                                <img src="{{ asset('portal/projects/' . $project->featured_image) }}"
                                     class="h-12 w-16 object-cover rounded">
                            @else
                                <div class="h-12 w-16 bg-gray-100 rounded flex items-center justify-center text-gray-300">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $project->title_nl }}</div>
                            @if($project->title_en)
                                <div class="text-xs text-gray-400">{{ $project->title_en }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $project->projectType?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'ongoing'    => 'bg-blue-100 text-blue-800',
                                    'completed'  => 'bg-green-100 text-green-800',
                                    'coming_soon'=> 'bg-yellow-100 text-yellow-800',
                                    'planning'   => 'bg-gray-100 text-gray-600',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $project->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $project->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                {{ $project->is_published ? 'Yes' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($project->is_featured)
                                <i class="fas fa-star text-yellow-400"></i>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('projects.show', $project->slug) }}" target="_blank" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this project and all its media?')">
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
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                            No projects yet. <a href="{{ route('admin.projects.create') }}" class="text-blue-600 hover:underline">Create your first project.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
