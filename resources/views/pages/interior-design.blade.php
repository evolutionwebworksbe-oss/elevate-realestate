<x-app-layout>
    <x-slot name="title">{{ __('messages.interior_design_page_title') }} - Elevate Real Estate</x-slot>

    {{-- ═══════════════════════════════════════════ HERO ══ --}}
    <section class="relative bg-gray-900 text-white overflow-hidden" style="min-height: 520px;">
        <div class="absolute inset-0">
            <img src="{{ asset('portal/img/interior-design-hero.jpg') }}"
                 onerror="this.style.display='none'"
                 class="w-full h-full object-cover opacity-30" alt="">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-900/80 to-primary/40"></div>
        </div>
        <div class="relative container mx-auto px-4 py-28 max-w-5xl">
            <p class="text-accent text-sm font-semibold uppercase tracking-widest mb-4">Elevate Real Estate</p>
            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6">{{ __('messages.interior_design_title') }}</h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-2xl leading-relaxed">
                {{ __('messages.interior_design_hero_subtitle') }}
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ INTRO ══ --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-12 items-start">
                <div class="md:col-span-3 space-y-5 text-gray-700 text-lg leading-relaxed">
                    <p>
                        {{ __('messages.interior_design_intro_p1') }}
                    </p>
                    <p>
                        {{ __('messages.interior_design_intro_p2') }}
                    </p>
                    <p>
                        {{ __('messages.interior_design_intro_p3') }}
                    </p>
                </div>
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-primary/5 border-l-4 border-primary rounded-r-xl p-5">
                        <i class="fas fa-phone text-primary mb-2"></i>
                        <p class="font-semibold text-gray-800">+597 8180018</p>
                    </div>
                    <div class="bg-primary/5 border-l-4 border-primary rounded-r-xl p-5">
                        <i class="fas fa-map-marker-alt text-primary mb-2"></i>
                        <p class="font-semibold text-gray-800">Elevate Real Estate — Paramaribo</p>
                    </div>
                    <a href="{{ route('contact') }}"
                       class="block text-center bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-xl transition">
                        {{ __('messages.interior_design_request_consultation') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ PORTFOLIO ══ --}}
    @if($projects->count() > 0)
    <section class="py-20 bg-gray-900 text-white overflow-hidden"
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

        <div class="container mx-auto px-4 max-w-6xl">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
                <div>
                    <p class="text-accent text-sm font-semibold uppercase tracking-widest mb-2">{{ __('messages.interior_design_portfolio_label') }}</p>
                    <h2 class="text-3xl md:text-4xl font-bold">{{ __('messages.interior_design_completed_projects') }}</h2>
                    <p class="text-gray-400 mt-2 max-w-xl">
                        {{ __('messages.interior_design_portfolio_description') }}
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button @click="prev()" :class="current === 0 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-accent'"
                            class="w-10 h-10 rounded-full border border-white border-opacity-30 flex items-center justify-center transition">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <button @click="next()" :class="current >= maxIndex() ? 'opacity-30 cursor-not-allowed' : 'hover:bg-accent'"
                            class="w-10 h-10 rounded-full border border-white border-opacity-30 flex items-center justify-center transition">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out gap-6"
                     :style="'transform: ' + translateX()">
                    @foreach($projects as $project)
                    <div class="shrink-0 w-full sm:w-1/2 lg:w-1/3">
                        <a href="{{ route('projects.show', $project->slug) }}"
                           class="group block bg-gray-800 rounded-2xl overflow-hidden hover:ring-2 hover:ring-accent transition-all">
                            <div class="relative h-52 bg-gray-700 overflow-hidden">
                                @if($project->featured_image)
                                    <img src="{{ asset('portal/projects/' . $project->featured_image) }}"
                                         alt="{{ $project->getTranslatedTitle() }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <i class="fas fa-couch text-5xl"></i>
                                    </div>
                                @endif
                                <span class="absolute top-4 left-4 text-xs font-semibold px-3 py-1 rounded-full
                                    {{ $project->status === 'completed'   ? 'bg-green-500 text-white'      : '' }}
                                    {{ $project->status === 'ongoing'     ? 'bg-blue-500 text-white'       : '' }}
                                    {{ $project->status === 'coming_soon' ? 'bg-yellow-400 text-gray-900'  : '' }}
                                    {{ $project->status === 'planning'    ? 'bg-gray-500 text-white'       : '' }}">
                                    {{ $project->status_label }}
                                </span>
                            </div>
                            <div class="p-5">
                                <h3 class="text-base font-bold text-white mb-1 group-hover:text-accent transition-colors">
                                    {{ $project->getTranslatedTitle() }}
                                </h3>
                                @if($project->getTranslatedExcerpt())
                                    <p class="text-gray-400 text-sm line-clamp-2">{{ $project->getTranslatedExcerpt() }}</p>
                                @endif
                                <p class="text-accent text-xs font-medium mt-3 group-hover:underline">
                                    {{ __('messages.interior_design_view_project') }} <i class="fas fa-arrow-right ml-1"></i>
                                </p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-center gap-2 mt-8">
                @foreach($projects as $i => $p)
                <button @click="current = {{ $i }} <= maxIndex() ? {{ $i }} : maxIndex()"
                        :class="current === {{ $i }} ? 'bg-accent w-6' : 'bg-gray-600 w-2'"
                        class="h-2 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('projects.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-3 border border-white border-opacity-30 hover:bg-white hover:text-gray-900 text-white font-semibold rounded-lg transition">
                    {{ __('messages.interior_design_view_all_projects') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════ WERKWIJZE ══ --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="text-center mb-16">
                <p class="text-accent text-sm font-semibold uppercase tracking-widest mb-3">Elevate Real Estate</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ __('messages.interior_design_process_title') }}</h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">
                    {{ __('messages.interior_design_process_intro') }}
                </p>
            </div>

            <div class="space-y-6">

                @php
                $steps = [
                    [
                        'number' => '01',
                        'icon'   => '📋',
                        'title'  => __('messages.interior_design_step_1_title'),
                        'desc'   => __('messages.interior_design_step_1_desc'),
                        'cost'   => __('messages.interior_design_step_1_cost'),
                        'cost_color' => 'green',
                    ],
                    [
                        'number' => '02',
                        'icon'   => '📍',
                        'title'  => __('messages.interior_design_step_2_title'),
                        'desc'   => __('messages.interior_design_step_2_desc'),
                        'cost'   => __('messages.interior_design_step_2_cost'),
                        'cost_color' => 'blue',
                    ],
                    [
                        'number' => '03',
                        'icon'   => '🎨',
                        'title'  => __('messages.interior_design_step_3_title'),
                        'desc'   => __('messages.interior_design_step_3_desc'),
                        'cost'   => __('messages.interior_design_step_3_cost'),
                        'cost_color' => 'green',
                    ],
                    [
                        'number' => '04',
                        'icon'   => '🏡',
                        'title'  => __('messages.interior_design_step_4_title'),
                        'desc'   => __('messages.interior_design_step_4_desc'),
                        'cost'   => __('messages.interior_design_step_4_cost'),
                        'cost_color' => 'purple',
                    ],
                    [
                        'number' => '05',
                        'icon'   => '🛠️',
                        'title'  => __('messages.interior_design_step_5_title'),
                        'desc'   => __('messages.interior_design_step_5_desc'),
                        'cost'   => __('messages.interior_design_step_5_cost'),
                        'cost_color' => 'green',
                    ],
                    [
                        'number' => '06',
                        'icon'   => '🪑',
                        'title'  => __('messages.interior_design_step_6_title'),
                        'desc'   => __('messages.interior_design_step_6_desc'),
                        'cost'   => __('messages.interior_design_step_6_cost'),
                        'cost_color' => 'purple',
                    ],
                ];
                $costColors = [
                    'green'  => 'bg-green-50 text-green-700 border border-green-200',
                    'blue'   => 'bg-blue-50 text-blue-700 border border-blue-200',
                    'purple' => 'bg-purple-50 text-purple-700 border border-purple-200',
                ];
                @endphp

                @foreach($steps as $step)
                <div class="flex gap-6 p-6 rounded-2xl border border-gray-100 hover:border-primary/30 hover:shadow-md transition-all bg-white group">
                    <div class="shrink-0 flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary font-bold text-sm flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                            {{ $step['number'] }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">
                                    <span class="mr-2">{{ $step['icon'] }}</span>{{ $step['title'] }}
                                </h3>
                                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                            <div class="shrink-0">
                                <span class="inline-block text-xs font-semibold px-3 py-1.5 rounded-lg whitespace-nowrap {{ $costColors[$step['cost_color']] }}">
                                    {{ $step['cost'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            <p class="text-center text-gray-500 italic mt-10 text-sm">
                {{ __('messages.interior_design_process_footer') }}
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ PAYMENT TERMS ══ --}}
    <section class="py-14 bg-gray-50 border-t border-gray-100">
        <div class="container mx-auto px-4 max-w-3xl text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('messages.interior_design_payment_terms_title') }}</h3>
            <p class="text-gray-600 leading-relaxed">
                {{ __('messages.interior_design_payment_terms_body') }}
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ WHY CHOOSE US ══ --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-accent text-sm font-semibold uppercase tracking-widest mb-3">{{ __('messages.interior_design_why_choose_label') }}</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        {!! __('messages.interior_design_why_choose_title') !!}
                    </h2>
                    <p class="text-gray-500 mb-8">
                        {{ __('messages.interior_design_why_choose_body') }}
                    </p>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white font-semibold px-7 py-3 rounded-xl transition">
                        {{ __('messages.interior_design_get_in_touch') }} <i class="fas fa-arrow-right"></i>
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
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-check text-green-600 text-xs"></i>
                        </div>
                        <p class="text-gray-700 font-medium">{{ $reason }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════ CTA ══ --}}
    <section class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4 text-center max-w-2xl">
            <h2 class="text-3xl font-bold mb-3">{{ __('messages.interior_design_cta_title') }}</h2>
            <p class="text-gray-200 mb-8">{{ __('messages.interior_design_cta_body') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:+5978180018"
                   class="inline-flex items-center justify-center gap-2 bg-white text-primary font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-phone"></i> +597 8180018
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center gap-2 bg-accent hover:bg-opacity-90 text-white font-semibold px-8 py-3 rounded-lg transition">
                    <i class="fas fa-envelope"></i> {{ __('messages.send_message') }}
                </a>
            </div>
        </div>
    </section>

</x-app-layout>
