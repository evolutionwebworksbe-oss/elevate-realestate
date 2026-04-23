<x-luxury-layout>
    <x-slot name="title">{{ $project->meta_title ?: $project->getTranslatedTitle() }} — Elevate Luxury Living</x-slot>

    {{-- ═══ HERO ══ --}}
    <section class="relative overflow-hidden" style="min-height: 480px; background-color: #0A0A0A;">
        @if($project->featured_image)
            <x-responsive-image
                :path="'portal/projects/' . $project->featured_image"
                :alt="$project->getTranslatedTitle()"
                sizes="100vw"
                class="absolute inset-0 w-full h-full object-cover"
                loading="eager"
                style="opacity:.2;"
            />
        @endif
        <div class="absolute inset-0" style="background: linear-gradient(to right, #0A0A0A 40%, rgba(10,10,10,0.5) 100%);"></div>

        <div class="relative max-w-6xl mx-auto px-6 py-28 flex flex-col justify-end" style="min-height: 480px;">
            {{-- Breadcrumb --}}
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-2 text-xs font-semibold tracking-[.2em] uppercase mb-8 transition"
               style="color:#C9A84C;">
                <i class="fas fa-arrow-left text-xs"></i> Projecten
            </a>

            {{-- Type & status --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($project->projectType)
                    <span class="text-xs font-semibold tracking-widest uppercase px-3 py-1 rounded-sm"
                          style="border:1px solid rgba(201,168,76,0.4); color:#C9A84C;">
                        {{ $project->projectType->getTranslatedName() }}
                    </span>
                @endif
                <span class="text-xs font-semibold px-3 py-1 rounded-sm"
                      style="{{ $project->status === 'completed' ? 'background:#16a34a; color:#fff;' : '' }}
                             {{ $project->status === 'ongoing'   ? 'background:#2563eb; color:#fff;' : '' }}
                             {{ $project->status === 'coming_soon' ? 'background:#d97706; color:#fff;' : '' }}
                             {{ $project->status === 'planning'  ? 'background:#4b5563; color:#fff;' : '' }}">
                    {{ $project->status_label }}
                </span>
            </div>

            <h1 class="lux-heading text-4xl md:text-6xl font-semibold leading-tight mb-4"
                style="font-family:'Cormorant Garamond',serif;">
                {{ $project->getTranslatedTitle() }}
            </h1>
            @if($project->getTranslatedExcerpt())
                <p class="text-lg max-w-2xl leading-relaxed" style="color:#D6CFC2;">
                    {{ $project->getTranslatedExcerpt() }}
                </p>
            @endif
        </div>
    </section>

    <div class="gold-divider"></div>

    {{-- ═══ CONTENT ══ --}}
    <section class="py-16 lux-bg-soft">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- ── Main ── --}}
                <div class="lg:col-span-2 space-y-12">

                    {{-- Description --}}
                    @if($project->getTranslatedDescription())
                    <div class="prose-luxury leading-relaxed text-lg" style="color:#D6CFC2;">
                        <style>
                            .prose-luxury h1,.prose-luxury h2,.prose-luxury h3 {
                                font-family:'Cormorant Garamond',serif;
                                color:#F5F0E8;
                                font-weight:600;
                                margin-bottom:.75rem;
                            }
                            .prose-luxury p { margin-bottom:1.25rem; color:#D6CFC2; }
                            .prose-luxury a { color:#C9A84C; }
                            .prose-luxury strong { color:#F5F0E8; }
                            .prose-luxury ul,.prose-luxury ol { padding-left:1.5rem; margin-bottom:1.25rem; }
                            .prose-luxury li { margin-bottom:.5rem; }
                        </style>
                        {!! $project->getTranslatedDescription() !!}
                    </div>
                    @endif

                    {{-- Gallery --}}
                    @if($project->images->count() > 0)
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <h2 class="lux-heading text-2xl font-semibold" style="font-family:'Cormorant Garamond',serif;">
                                {{ app()->getLocale() === 'en' ? 'Gallery' : 'Galerij' }}
                            </h2>
                            <div class="flex-1 gold-divider"></div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($project->images as $image)
                            <a href="{{ asset('portal/projects/gallery/' . $image->path) }}" target="_blank"
                               class="group relative overflow-hidden rounded-sm" style="height:160px; background:#1C1C1C;">
                                <x-responsive-image
                                    :path="'portal/projects/gallery/' . $image->path"
                                    :alt="$image->alt_text ?: $project->getTranslatedTitle()"
                                    sizes="(min-width: 640px) 33vw, 50vw"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Videos --}}
                    @if($project->videos->count() > 0)
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <h2 class="lux-heading text-2xl font-semibold" style="font-family:'Cormorant Garamond',serif;">
                                Videos
                            </h2>
                            <div class="flex-1 gold-divider"></div>
                        </div>
                        <div class="space-y-6">
                            @foreach($project->videos as $video)
                            <div class="lux-card rounded-sm overflow-hidden">
                                @if($video->title)
                                    <p class="px-5 pt-4 text-sm font-semibold" style="color:#D6CFC2;">{{ $video->title }}</p>
                                @endif
                                <div class="relative" style="padding-top:56.25%; background:#0A0A0A;">
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

                {{-- ── Sidebar ── --}}
                <div class="space-y-5">

                    {{-- Project details --}}
                    <div class="rounded-sm p-6" style="background:#141414; border:1px solid rgba(201,168,76,0.15);">
                        <h3 class="text-xs font-semibold tracking-[.2em] uppercase mb-5" style="color:#C9A84C;">
                            Project Details
                        </h3>
                        <dl class="space-y-4 text-sm">
                            @if($project->location)
                            <div>
                                <dt class="text-xs uppercase tracking-widest mb-1" style="color:#5A5040;">
                                    <i class="fas fa-map-marker-alt mr-2" style="color:#C9A84C;"></i>{{ app()->getLocale() === 'en' ? 'Location' : 'Locatie' }}
                                </dt>
                                <dd class="font-medium" style="color:#F5F0E8;">{{ $project->location }}</dd>
                            </div>
                            @endif
                            @if($project->total_units)
                            <div style="border-top:1px solid rgba(201,168,76,0.08); padding-top:1rem;">
                                <dt class="text-xs uppercase tracking-widest mb-1" style="color:#5A5040;">
                                    <i class="fas fa-home mr-2" style="color:#C9A84C;"></i>{{ app()->getLocale() === 'en' ? 'Total Units' : 'Totaal Units' }}
                                </dt>
                                <dd class="font-medium" style="color:#F5F0E8;">{{ $project->total_units }}</dd>
                            </div>
                            @endif
                            @if($project->total_area)
                            <div style="border-top:1px solid rgba(201,168,76,0.08); padding-top:1rem;">
                                <dt class="text-xs uppercase tracking-widest mb-1" style="color:#5A5040;">
                                    <i class="fas fa-ruler-combined mr-2" style="color:#C9A84C;"></i>{{ app()->getLocale() === 'en' ? 'Total Area' : 'Totale Oppervlakte' }}
                                </dt>
                                <dd class="font-medium" style="color:#F5F0E8;">{{ number_format($project->total_area) }} m²</dd>
                            </div>
                            @endif
                            @if($project->start_date)
                            <div style="border-top:1px solid rgba(201,168,76,0.08); padding-top:1rem;">
                                <dt class="text-xs uppercase tracking-widest mb-1" style="color:#5A5040;">
                                    <i class="fas fa-calendar-alt mr-2" style="color:#C9A84C;"></i>{{ app()->getLocale() === 'en' ? 'Start Date' : 'Startdatum' }}
                                </dt>
                                <dd class="font-medium" style="color:#F5F0E8;">{{ $project->start_date->format('M Y') }}</dd>
                            </div>
                            @endif
                            @if($project->end_date)
                            <div style="border-top:1px solid rgba(201,168,76,0.08); padding-top:1rem;">
                                <dt class="text-xs uppercase tracking-widest mb-1" style="color:#5A5040;">
                                    <i class="fas fa-calendar-check mr-2" style="color:#C9A84C;"></i>{{ app()->getLocale() === 'en' ? 'End Date' : 'Einddatum' }}
                                </dt>
                                <dd class="font-medium" style="color:#F5F0E8;">{{ $project->end_date->format('M Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Downloads --}}
                    @if($project->downloads->count() > 0)
                    <div class="rounded-sm p-6" style="background:#141414; border:1px solid rgba(201,168,76,0.15);">
                        <h3 class="text-xs font-semibold tracking-[.2em] uppercase mb-5" style="color:#C9A84C;">
                            <i class="fas fa-download mr-2"></i>Downloads
                        </h3>
                        <div class="space-y-2">
                            @foreach($project->downloads as $download)
                            <a href="{{ $download->getDownloadUrl() }}" target="_blank"
                               class="flex items-center gap-3 p-3 rounded-sm transition group"
                               style="border:1px solid rgba(201,168,76,0.1);"
                               onmouseover="this.style.borderColor='rgba(201,168,76,0.4)'"
                               onmouseout="this.style.borderColor='rgba(201,168,76,0.1)'">
                                <i class="fas fa-file-arrow-down text-lg" style="color:#C9A84C;"></i>
                                <span class="text-sm font-medium flex-1" style="color:#D6CFC2;">{{ $download->label }}</span>
                                <i class="fas fa-external-link-alt text-xs" style="color:#5A5040;"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- CTA --}}
                    <div class="rounded-sm p-6 text-center" style="background:linear-gradient(135deg,#141410,#1C1A10); border:1px solid rgba(201,168,76,0.2);">
                        <p class="text-xs font-semibold tracking-[.2em] uppercase mb-3" style="color:#C9A84C;">Interesse?</p>
                        <p class="text-sm mb-5 lux-muted">Neem contact op voor meer informatie over dit project.</p>
                        <a href="{{ route('contact') }}"
                           class="btn-gold inline-flex items-center justify-center gap-2 w-full py-3 rounded-sm text-xs tracking-widest uppercase">
                            <i class="fas fa-envelope text-xs"></i> Contact
                        </a>
                    </div>

                    {{-- Back --}}
                    <a href="{{ route('projects.index') }}"
                       class="flex items-center justify-center gap-2 py-3 rounded-sm text-xs font-semibold tracking-[.2em] uppercase transition"
                       style="border:1px solid rgba(201,168,76,0.2); color:#9A9080;"
                       onmouseover="this.style.color='#C9A84C'; this.style.borderColor='rgba(201,168,76,0.4)'"
                       onmouseout="this.style.color='#9A9080'; this.style.borderColor='rgba(201,168,76,0.2)'">
                        <i class="fas fa-arrow-left text-xs"></i>
                        {{ app()->getLocale() === 'en' ? 'All Projects' : 'Alle Projecten' }}
                    </a>

                </div>
            </div>
        </div>
    </section>

</x-luxury-layout>
