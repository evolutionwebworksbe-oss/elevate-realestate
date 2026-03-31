<x-app-layout>
    <x-slot name="title">{{ $project->meta_title ?: $project->getTranslatedTitle() }} - Elevate Real Estate</x-slot>

    <!-- Hero -->
    <div class="relative bg-gray-900 text-white">
        @if($project->featured_image)
            <img src="{{ asset('portal/projects/' . $project->featured_image) }}"
                 class="absolute inset-0 w-full h-full object-cover opacity-40" alt="{{ $project->getTranslatedTitle() }}">
        @endif
        <div class="relative container mx-auto px-4 py-24 max-w-5xl">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($project->projectType)
                    <span class="bg-white bg-opacity-20 text-white text-sm px-3 py-1 rounded-full">
                        {{ $project->projectType->getTranslatedName() }}
                    </span>
                @endif
                <span class="text-sm px-3 py-1 rounded-full
                    {{ $project->status === 'completed'  ? 'bg-green-600' : '' }}
                    {{ $project->status === 'ongoing'    ? 'bg-blue-600'  : '' }}
                    {{ $project->status === 'coming_soon'? 'bg-yellow-500': '' }}
                    {{ $project->status === 'planning'   ? 'bg-gray-600'  : '' }}">
                    {{ $project->status_label }}
                </span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $project->getTranslatedTitle() }}</h1>
            @if($project->getTranslatedExcerpt())
                <p class="text-xl text-gray-200 max-w-3xl">{{ $project->getTranslatedExcerpt() }}</p>
            @endif
        </div>
    </div>

    <div class="py-12 bg-white">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                <!-- Main content -->
                <div class="lg:col-span-2 space-y-10">

                    <!-- Description -->
                    @if($project->getTranslatedDescription())
                    <div class="prose prose-lg max-w-none">
                        {!! $project->getTranslatedDescription() !!}
                    </div>
                    @endif

                    <!-- Gallery -->
                    @if($project->images->count() > 0)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-5">
                            {{ app()->getLocale() === 'en' ? 'Gallery' : 'Galerij' }}
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($project->images as $image)
                            <a href="{{ asset('portal/projects/gallery/' . $image->path) }}" target="_blank">
                                <img src="{{ asset('portal/projects/gallery/' . $image->path) }}"
                                     alt="{{ $image->alt_text ?: $project->getTranslatedTitle() }}"
                                     class="w-full h-40 object-cover rounded-lg hover:opacity-90 transition">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Videos -->
                    @if($project->videos->count() > 0)
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-5">Videos</h2>
                        <div class="space-y-6">
                            @foreach($project->videos as $video)
                            <div>
                                @if($video->title)
                                    <h3 class="text-base font-semibold text-gray-700 mb-2">{{ $video->title }}</h3>
                                @endif
                                <div class="relative rounded-xl overflow-hidden bg-black" style="padding-top:56.25%">
                                    <iframe src="{{ $video->getEmbedUrl() }}"
                                            class="absolute inset-0 w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Project details -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            {{ app()->getLocale() === 'en' ? 'Project Details' : 'Project Details' }}
                        </h3>
                        <dl class="space-y-3 text-sm">
                            @if($project->location)
                            <div class="flex justify-between">
                                <dt class="text-gray-500"><i class="fas fa-map-marker-alt mr-2"></i>{{ app()->getLocale() === 'en' ? 'Location' : 'Locatie' }}</dt>
                                <dd class="font-medium text-gray-800">{{ $project->location }}</dd>
                            </div>
                            @endif
                            @if($project->total_units)
                            <div class="flex justify-between">
                                <dt class="text-gray-500"><i class="fas fa-home mr-2"></i>{{ app()->getLocale() === 'en' ? 'Total Units' : 'Totaal Units' }}</dt>
                                <dd class="font-medium text-gray-800">{{ $project->total_units }}</dd>
                            </div>
                            @endif
                            @if($project->total_area)
                            <div class="flex justify-between">
                                <dt class="text-gray-500"><i class="fas fa-ruler-combined mr-2"></i>{{ app()->getLocale() === 'en' ? 'Total Area' : 'Totale Oppervlakte' }}</dt>
                                <dd class="font-medium text-gray-800">{{ number_format($project->total_area) }} m²</dd>
                            </div>
                            @endif
                            @if($project->start_date)
                            <div class="flex justify-between">
                                <dt class="text-gray-500"><i class="fas fa-calendar-alt mr-2"></i>{{ app()->getLocale() === 'en' ? 'Start Date' : 'Startdatum' }}</dt>
                                <dd class="font-medium text-gray-800">{{ $project->start_date->format('M Y') }}</dd>
                            </div>
                            @endif
                            @if($project->end_date)
                            <div class="flex justify-between">
                                <dt class="text-gray-500"><i class="fas fa-calendar-check mr-2"></i>{{ app()->getLocale() === 'en' ? 'End Date' : 'Einddatum' }}</dt>
                                <dd class="font-medium text-gray-800">{{ $project->end_date->format('M Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Downloads -->
                    @if($project->downloads->count() > 0)
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-download mr-2 text-green-600"></i>Downloads
                        </h3>
                        <div class="space-y-2">
                            @foreach($project->downloads as $download)
                            <a href="{{ $download->getDownloadUrl() }}" target="_blank"
                               class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg hover:border-green-400 hover:bg-green-50 transition group">
                                <i class="fas fa-file-arrow-down text-green-600 text-lg"></i>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">{{ $download->label }}</span>
                                <i class="fas fa-external-link-alt text-gray-300 text-xs ml-auto"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Back link -->
                    <a href="{{ route('projects.index') }}"
                       class="block text-center text-sm text-primary hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i>
                        {{ app()->getLocale() === 'en' ? 'Back to all projects' : 'Terug naar alle projecten' }}
                    </a>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
