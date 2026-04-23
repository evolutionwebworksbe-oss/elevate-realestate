<x-luxury-layout>
    <x-slot name="title">Projecten — Elevate Luxury Living</x-slot>

    {{-- ═══ HERO ══ --}}
    <section class="relative overflow-hidden" style="background-color: #0A0A0A; min-height: 360px;">
        <div class="absolute inset-0 opacity-10"
             style="background: radial-gradient(circle at top right, #C9A84C, transparent 65%);"></div>
        <div class="relative max-w-6xl mx-auto px-6 py-28 flex flex-col justify-center" style="min-height: 360px;">
            <p class="text-xs font-semibold uppercase tracking-[.3em] mb-5" style="color: #C9A84C;">Elevate Luxury Living</p>
            <h1 class="lux-heading text-5xl md:text-6xl font-semibold mb-6" style="font-family: 'Cormorant Garamond', serif;">
                {{ app()->getLocale() === 'en' ? 'Our Projects' : 'Onze Projecten' }}
            </h1>
            <div class="gold-divider w-24"></div>
        </div>
    </section>

    {{-- ═══ FILTERS ══ --}}
    @if($projectTypes->count() > 0)
    <div style="background-color: #141414; border-bottom: 1px solid rgba(201,168,76,0.15);">
        <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap gap-2">
            <a href="{{ route('projects.index') }}"
               class="px-4 py-2 rounded-sm text-xs font-semibold tracking-[.15em] uppercase transition"
               style="{{ !request('type') ? 'background: linear-gradient(135deg,#C9A84C,#E2C97E); color:#0A0A0A;' : 'border:1px solid rgba(201,168,76,0.25); color:#9A9080;' }}">
                {{ app()->getLocale() === 'en' ? 'All' : 'Alles' }}
            </a>
            @foreach($projectTypes as $type)
            <a href="{{ route('projects.index', ['type' => $type->id]) }}"
               class="px-4 py-2 rounded-sm text-xs font-semibold tracking-[.15em] uppercase transition"
               style="{{ request('type') == $type->id ? 'background: linear-gradient(135deg,#C9A84C,#E2C97E); color:#0A0A0A;' : 'border:1px solid rgba(201,168,76,0.25); color:#9A9080;' }}">
                {{ $type->getTranslatedName() }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ GRID ══ --}}
    <section class="py-20 lux-bg-soft">
        <div class="max-w-6xl mx-auto px-6">
            @if($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}"
                       class="group lux-card rounded-sm overflow-hidden block">

                        {{-- Image --}}
                        <div class="relative overflow-hidden" style="height: 220px; background:#1C1C1C;">
                            @if($project->featured_image)
                                <x-responsive-image
                                    :path="'portal/projects/' . $project->featured_image"
                                    :alt="$project->getTranslatedTitle()"
                                    sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                />
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color:#2A2A2A;">
                                    <i class="fas fa-building text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-all duration-500"></div>

                            @if($project->is_featured)
                                <span class="absolute top-3 left-3 text-xs font-semibold px-3 py-1 rounded-sm"
                                      style="background:linear-gradient(135deg,#C9A84C,#E2C97E); color:#0A0A0A;">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-3 text-xs" style="color:#6A6050;">
                                @if($project->projectType)
                                    <span style="color:#C9A84C; font-weight:600;">{{ $project->projectType->getTranslatedName() }}</span>
                                    <span>·</span>
                                @endif
                                @if($project->location)
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $project->location }}</span>
                                @endif
                            </div>

                            <h3 class="font-semibold mb-2 group-hover:text-[#C9A84C] transition-colors"
                                style="color:#F5F0E8; letter-spacing:.02em;">
                                {{ $project->getTranslatedTitle() }}
                            </h3>

                            @if($project->getTranslatedExcerpt())
                                <p class="text-sm line-clamp-2 mb-4" style="color:#6A6050;">
                                    {{ $project->getTranslatedExcerpt() }}
                                </p>
                            @endif

                            <div class="flex items-center gap-4 pt-4 text-xs" style="border-top:1px solid rgba(201,168,76,0.1); color:#5A5040;">
                                @if($project->total_units)
                                    <span><i class="fas fa-home mr-1"></i>{{ $project->total_units }} units</span>
                                @endif
                                @if($project->total_area)
                                    <span><i class="fas fa-ruler-combined mr-1"></i>{{ number_format($project->total_area) }} m²</span>
                                @endif
                                <span class="ml-auto text-xs font-semibold tracking-widest uppercase" style="color:#C9A84C;">
                                    Bekijk <i class="fas fa-arrow-right ml-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-28">
                    <i class="fas fa-building text-5xl mb-6" style="color:#2A2A2A;"></i>
                    <p class="lux-muted text-lg">{{ app()->getLocale() === 'en' ? 'No projects yet.' : 'Nog geen projecten.' }}</p>
                </div>
            @endif
        </div>
    </section>

</x-luxury-layout>
