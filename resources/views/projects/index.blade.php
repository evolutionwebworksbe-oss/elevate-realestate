<x-app-layout>
    <x-slot name="title">Projecten - Elevate Real Estate</x-slot>

    <!-- Hero -->
    <div class="bg-gradient-to-br from-primary-dark to-primary text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ app()->getLocale() === 'en' ? 'Our Projects' : 'Onze Projecten' }}</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                {{ app()->getLocale() === 'en' ? 'Discover our real estate developments and portfolio.' : 'Ontdek onze vastgoedontwikkelingen en portfolio.' }}
            </p>
        </div>
    </div>

    <!-- Filters -->
    @if($projectTypes->count() > 0)
    <div class="bg-white border-b py-4">
        <div class="container mx-auto px-4 flex flex-wrap gap-3 justify-center">
            <a href="{{ route('projects.index') }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ !request('type') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ app()->getLocale() === 'en' ? 'All' : 'Alles' }}
            </a>
            @foreach($projectTypes as $type)
            <a href="{{ route('projects.index', ['type' => $type->id]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') == $type->id ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $type->getTranslatedName() }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Projects Grid -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 max-w-6xl">
            @if($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}"
                       class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                        <div class="relative h-52 bg-gray-100 overflow-hidden">
                            @if($project->featured_image)
                                <img src="{{ asset('portal/projects/' . $project->featured_image) }}"
                                     alt="{{ $project->getTranslatedTitle() }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-building text-5xl"></i>
                                </div>
                            @endif
                            @if($project->is_featured)
                                <span class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                            <span class="absolute top-3 right-3 text-xs font-medium px-2 py-1 rounded
                                {{ $project->status === 'completed' ? 'bg-green-600 text-white' : '' }}
                                {{ $project->status === 'ongoing' ? 'bg-blue-600 text-white' : '' }}
                                {{ $project->status === 'coming_soon' ? 'bg-yellow-500 text-white' : '' }}
                                {{ $project->status === 'planning' ? 'bg-gray-600 text-white' : '' }}">
                                {{ $project->status_label }}
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                @if($project->projectType)
                                    <span class="text-xs text-primary font-medium">{{ $project->projectType->getTranslatedName() }}</span>
                                @endif
                                @if($project->location)
                                    <span class="text-xs text-gray-400"><i class="fas fa-map-marker-alt mr-1"></i>{{ $project->location }}</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $project->getTranslatedTitle() }}</h3>
                            @if($project->getTranslatedExcerpt())
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $project->getTranslatedExcerpt() }}</p>
                            @endif
                            <div class="flex items-center gap-4 mt-4 text-xs text-gray-400">
                                @if($project->total_units)
                                    <span><i class="fas fa-home mr-1"></i>{{ $project->total_units }} units</span>
                                @endif
                                @if($project->total_area)
                                    <span><i class="fas fa-ruler-combined mr-1"></i>{{ number_format($project->total_area) }} m²</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-building text-5xl mb-4"></i>
                    <p class="text-lg">{{ app()->getLocale() === 'en' ? 'No projects yet.' : 'Nog geen projecten.' }}</p>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
