<x-luxury-layout>
    <x-slot name="title">{{ __('messages.interior_design_page_title') }} — Elevate Luxury Living</x-slot>

    {{-- ═══════════════════════════════════════════ HERO ══ --}}
    <section class="relative overflow-hidden" style="min-height: 580px; background-color: #0A0A0A;">
        <div class="absolute inset-0">
            <img src="{{ asset('portal/img/interior-design-hero.jpg') }}"
                 onerror="this.style.display='none'"
                 class="w-full h-full object-cover opacity-20" alt="">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, #0A0A0A 40%, rgba(10,10,10,0.6) 100%);"></div>
        </div>

        {{-- Gold corner accent --}}
        <div class="absolute top-0 right-0 w-96 h-96 opacity-10"
             style="background: radial-gradient(circle at top right, #C9A84C, transparent 70%);"></div>

        <div class="relative max-w-6xl mx-auto px-6 py-32 flex flex-col justify-center" style="min-height: 580px;">
            <p class="text-xs font-semibold uppercase tracking-[.3em] mb-6" style="color: #C9A84C;">{{ __('messages.interior_design_brand_label') }}</p>
            <h1 class="lux-heading text-5xl md:text-7xl font-semibold leading-tight mb-6" style="font-family: 'Cormorant Garamond', serif;">
                {{ __('messages.interior_design_title') }}
            </h1>
            <div class="gold-divider w-24 mb-8"></div>
            <p class="text-xl md:text-2xl leading-relaxed max-w-2xl" style="color: #D6CFC2;">
                {{ __('messages.interior_design_hero_subtitle') }}
            </p>
            <div class="mt-10">
                <a href="{{ route('contact') }}"
                   class="btn-gold inline-flex items-center gap-3 px-8 py-4 rounded-sm text-sm tracking-widest uppercase">
                    <i class="fas fa-envelope text-xs"></i> {{ __('messages.interior_design_cta_button') }}
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ INTRO ══ --}}
    <section class="py-24 lux-bg-soft">
        <div class="max-w-6xl mx-auto px-6">
            <div class="max-w-3xl">
                <p class="text-xs font-semibold uppercase tracking-[.3em] mb-6 lux-gold-text">{{ __('messages.interior_design_about_label') }}</p>
                <div class="space-y-6 text-lg leading-relaxed" style="color: #D6CFC2;">
                    <p>{{ __('messages.interior_design_intro_p1') }}</p>
                    <p>{{ __('messages.interior_design_intro_p2') }}</p>
                    <p>{{ __('messages.interior_design_intro_p3') }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="gold-divider"></div>

    {{-- ═══════════════════════════════════════════ PORTFOLIO ══ --}}
    @if($projects->count() > 0)
    <section class="py-24 lux-bg-black overflow-hidden"
             x-data="{
                current: 0,
                total: {{ $projects->count() }},
                visibleCount() { return window.innerWidth >= 1024 ? 3 : window.innerWidth >= 640 ? 2 : 1; },
                maxIndex() { return Math.max(0, this.total - this.visibleCount()); },
                prev() { this.current = Math.max(0, this.current - 1); },
                next() { this.current = Math.min(this.maxIndex(), this.current + 1); },
                translateX() { return 'translateX(-' + (this.current * (100 / this.visibleCount())) + '%)'; }
             }"
             x-init="setInterval(() => { current < maxIndex() ? next() : current = 0 }, 4500)">

        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-14 gap-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[.3em] mb-4 lux-gold-text">{{ __('messages.interior_design_portfolio_label') }}</p>
                    <h2 class="lux-heading text-4xl md:text-5xl font-semibold" style="font-family: 'Cormorant Garamond', serif;">{{ __('messages.interior_design_projects_heading') }}</h2>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button @click="prev()" :class="current === 0 ? 'opacity-20 cursor-not-allowed' : 'hover:border-[#C9A84C] hover:text-[#C9A84C]'"
                            class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center transition text-white/60">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button @click="next()" :class="current >= maxIndex() ? 'opacity-20 cursor-not-allowed' : 'hover:border-[#C9A84C] hover:text-[#C9A84C]'"
                            class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center transition text-white/60">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out gap-6"
                     :style="'transform: ' + translateX()">
                    @foreach($projects as $project)
                    <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3">
                        <a href="{{ route('projects.show', $project->slug) }}"
                           class="group block lux-card rounded-sm overflow-hidden">
                            <div class="relative h-56 overflow-hidden" style="background: #1C1C1C;">
                                @if($project->featured_image)
                                    <x-responsive-image
                                        :path="'portal/projects/' . $project->featured_image"
                                        :alt="$project->getTranslatedTitle()"
                                        sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    />
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="color: #3A3A3A;">
                                        <i class="fas fa-couch text-5xl"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-500"></div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-base font-semibold mb-2 group-hover:text-[#C9A84C] transition-colors" style="color: #F5F0E8; letter-spacing: .02em;">
                                    {{ $project->getTranslatedTitle() }}
                                </h3>
                                @if($project->getTranslatedExcerpt())
                                    <p class="text-sm line-clamp-2" style="color: #9A9080;">{{ $project->getTranslatedExcerpt() }}</p>
                                @endif
                                <p class="text-xs font-medium mt-4 tracking-widest uppercase" style="color: #C9A84C;">
                                    {{ __('messages.interior_design_view_project') }} <i class="fas fa-arrow-right ml-1"></i>
                                </p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Dot indicators --}}
            <div class="flex justify-center gap-2 mt-10">
                @foreach($projects as $i => $p)
                <button @click="current = {{ $i }} <= maxIndex() ? {{ $i }} : maxIndex()"
                        :class="current === {{ $i }} ? 'w-6' : 'w-2'"
                        class="h-1.5 rounded-full transition-all duration-300"
                        :style="current === {{ $i }} ? 'background:#C9A84C' : 'background:rgba(201,168,76,0.2)'"></button>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('projects.index') }}"
                   class="btn-gold-outline inline-flex items-center gap-2 px-8 py-3 rounded-sm text-sm tracking-widest uppercase">
                    {{ __('messages.interior_design_view_all_projects') }} <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <div class="gold-divider"></div>
    @endif

    {{-- ═══════════════════════════════════════════ WERKWIJZE ══ --}}
    <section class="py-24 lux-bg-soft">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="text-xs font-semibold uppercase tracking-[.3em] mb-4 lux-gold-text">{{ __('messages.interior_design_brand_label') }}</p>
                <h2 class="lux-heading text-4xl md:text-5xl font-semibold mb-5" style="font-family: 'Cormorant Garamond', serif;">
                    {{ __('messages.interior_design_process_title') }}
                </h2>
                <div class="gold-divider w-24 mx-auto mb-6"></div>
                <p class="lux-muted max-w-2xl mx-auto leading-relaxed">
                    {{ __('messages.interior_design_process_intro') }}
                </p>
            </div>

            <div class="space-y-4">
                @php
                $steps = [
                    [
                        'number' => '01',
                        'title'  => __('messages.interior_design_step_1_title'),
                        'desc'   => __('messages.interior_design_step_1_desc'),
                        'cost'   => __('messages.interior_design_step_1_cost'),
                    ],
                    [
                        'number' => '02',
                        'title'  => __('messages.interior_design_step_2_title'),
                        'desc'   => __('messages.interior_design_step_2_desc'),
                        'cost'   => __('messages.interior_design_step_2_cost'),
                    ],
                    [
                        'number' => '03',
                        'title'  => __('messages.interior_design_step_3_title'),
                        'desc'   => __('messages.interior_design_step_3_desc'),
                        'cost'   => __('messages.interior_design_step_3_cost'),
                    ],
                    [
                        'number' => '04',
                        'title'  => __('messages.interior_design_step_4_title'),
                        'desc'   => __('messages.interior_design_step_4_desc'),
                        'cost'   => __('messages.interior_design_step_4_cost'),
                    ],
                    [
                        'number' => '05',
                        'title'  => __('messages.interior_design_step_5_title'),
                        'desc'   => __('messages.interior_design_step_5_desc'),
                        'cost'   => __('messages.interior_design_step_5_cost'),
                    ],
                    [
                        'number' => '06',
                        'title'  => __('messages.interior_design_step_6_title'),
                        'desc'   => __('messages.interior_design_step_6_desc'),
                        'cost'   => __('messages.interior_design_step_6_cost'),
                    ],
                ];
                @endphp

                @foreach($steps as $step)
                <div class="lux-step-row flex gap-6 p-6 rounded-sm" style="background-color: #141414;">
                    <div class="shrink-0">
                        <div class="lux-step-number w-12 h-12 rounded-full flex items-center justify-center text-sm">
                            {{ $step['number'] }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold mb-2 tracking-wide" style="color: #F5F0E8; letter-spacing: .03em;">
                            {{ $step['title'] }}
                        </h3>
                        <p class="text-sm leading-relaxed lux-muted mb-4">{{ $step['desc'] }}</p>
                        <span class="lux-cost-badge inline-block" style="white-space: normal; word-break: break-word;">{{ $step['cost'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <p class="text-center lux-muted italic mt-12 text-sm">
                {{ __('messages.interior_design_process_footer') }}
            </p>
        </div>
    </section>

    <div class="gold-divider"></div>

    {{-- ═══════════════════════════════════════════ WHY CHOOSE US ══ --}}
    <section class="py-24 lux-bg-black">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[.3em] mb-4 lux-gold-text">{{ __('messages.interior_design_why_choose_label') }}</p>
                    <h2 class="lux-heading text-4xl md:text-5xl font-semibold leading-tight mb-6" style="font-family: 'Cormorant Garamond', serif;">
                        {{ __('messages.interior_design_why_choose_title') }}
                    </h2>
                    <div class="gold-divider w-20 mb-8"></div>
                    <p class="lux-muted leading-relaxed mb-10">
                        {{ __('messages.interior_design_why_choose_body') }}
                    </p>
                    <a href="{{ route('contact') }}"
                       class="btn-gold inline-flex items-center gap-3 px-8 py-4 rounded-sm text-sm tracking-widest uppercase">
                        {{ __('messages.interior_design_get_in_touch') }} <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    @php
                    $reasons = [
                        __('messages.interior_design_reason_1'),
                        __('messages.interior_design_reason_2'),
                        __('messages.interior_design_reason_3'),
                        __('messages.interior_design_reason_4'),
                        __('messages.interior_design_reason_5'),
                    ];
                    @endphp
                    @foreach($reasons as $reason)
                    <div class="flex items-start gap-4 p-5 rounded-sm lux-bg-card" style="border-left: 2px solid #C9A84C;">
                        <i class="fas fa-check text-xs mt-1" style="color: #C9A84C;"></i>
                        <p class="text-sm leading-relaxed" style="color: #D6CFC2;">{{ $reason }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ CTA ══ --}}
    <section class="py-20 relative overflow-hidden" style="background: linear-gradient(135deg, #0A0A0A 0%, #1C1410 100%);">
        <div class="absolute inset-0 opacity-5"
             style="background: radial-gradient(circle at center, #C9A84C 0%, transparent 70%);"></div>
        <div class="relative max-w-3xl mx-auto px-6 text-center">
            <p class="text-xs font-semibold uppercase tracking-[.3em] mb-6 lux-gold-text">Elevate Luxury Living</p>
            <h2 class="lux-heading text-4xl md:text-5xl font-semibold mb-4" style="font-family: 'Cormorant Garamond', serif;">
                {{ __('messages.interior_design_cta_title') }}
            </h2>
            <div class="gold-divider w-24 mx-auto my-8"></div>
            <p class="lux-muted mb-10 text-lg">{{ __('messages.interior_design_cta_body') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:+5978180018"
                   class="btn-gold inline-flex items-center justify-center gap-3 px-8 py-4 rounded-sm text-sm tracking-widest uppercase">
                    <i class="fas fa-phone text-xs"></i> +597 8180018
                </a>
                <a href="{{ route('contact') }}"
                   class="btn-gold-outline inline-flex items-center justify-center gap-3 px-8 py-4 rounded-sm text-sm tracking-widest uppercase">
                    <i class="fas fa-envelope text-xs"></i> {{ __('messages.send_message') }}
                </a>
            </div>
        </div>
    </section>

</x-luxury-layout>
