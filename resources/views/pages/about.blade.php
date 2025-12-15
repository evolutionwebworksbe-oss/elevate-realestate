<x-app-layout>
    <x-slot name="title">{{ __('messages.about') }} - Elevate Real Estate</x-slot>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-primary-dark to-primary text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                {{ __('messages.about_elevate') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 max-w-4xl mx-auto">
                {{ __('messages.about_intro') }}
            </p>
        </div>
    </div>

    <!-- Team Section - MOVED UP -->
    @if($teamMembers->count() > 0)
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-6xl">
            <h2 class="text-3xl font-bold text-dark mb-12 text-center">{{ __('messages.our_team') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($teamMembers as $member)
                <a href="{{ route('team.profile', $member) }}" class="group">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="aspect-square overflow-hidden bg-gray-200">
                            @if($member->image)
                                <img src="{{ asset('portal/' . $member->image) }}" 
                                     alt="{{ $member->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-primary text-white text-6xl font-bold">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="font-bold text-dark text-lg mb-2 group-hover:text-primary transition">{{ $member->name }}</h3>
                            @if($member->titleTypes->count() > 0)
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @foreach($member->titleTypes as $title)
                                        <span class="px-3 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                            {{ app()->getLocale() == 'en' && $title->name_en ? $title->name_en : $title->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-600">{{ __('messages.team_member') }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Mission Statement -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="bg-light rounded-2xl p-8 md:p-12">
                <h2 class="text-3xl font-bold text-dark mb-6 text-center">{{ __('messages.our_mission') }}</h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    {{ __('messages.mission_p1') }}
                </p>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    {{ __('messages.mission_p2') }}
                </p>
                <p class="text-lg text-gray-700 leading-relaxed">
                    {{ __('messages.mission_p3') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Core Values -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 max-w-6xl">
            <h2 class="text-3xl font-bold text-dark mb-12 text-center">{{ __('messages.core_values') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">{{ __('messages.integrity') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.integrity_desc') }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">{{ __('messages.customer_satisfaction') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.customer_satisfaction_desc') }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-eye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">{{ __('messages.transparency') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.transparency_desc') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-5xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">{{ __('messages.what_we_do') }}</h2>
                <p class="text-lg text-gray-600">
                    {{ __('messages.what_we_do_desc') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-4 p-6 bg-light rounded-xl">
                    <i class="fas fa-home text-primary text-3xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-dark mb-2">{{ __('messages.buy_sell') }}</h3>
                        <p class="text-gray-600">{{ __('messages.buy_sell_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-6 bg-light rounded-xl">
                    <i class="fas fa-key text-primary text-3xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-dark mb-2">{{ __('messages.rental') }}</h3>
                        <p class="text-gray-600">{{ __('messages.rental_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-6 bg-light rounded-xl">
                    <i class="fas fa-clipboard-check text-primary text-3xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-dark mb-2">{{ __('messages.property_management') }}</h3>
                        <p class="text-gray-600">{{ __('messages.property_management_desc') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-6 bg-light rounded-xl">
                    <i class="fas fa-handshake text-primary text-3xl mt-1"></i>
                    <div>
                        <h3 class="font-bold text-dark mb-2">{{ __('messages.consulting') }}</h3>
                        <p class="text-gray-600">{{ __('messages.consulting_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quote Section -->
    <div class="py-20 bg-gradient-to-br from-primary-dark to-primary text-white">
        <div class="container mx-auto px-4 max-w-4xl text-center">
            <blockquote class="text-xl md:text-2xl italic mb-6 leading-relaxed">
                "{{ __('messages.founder_quote') }}"
            </blockquote>
            <footer class="text-lg">
                <strong class="block text-accent mb-2">Carol Rozenblad-Fränkel</strong>
                <span class="text-gray-300">{{ __('messages.founder_title') }}</span>
            </footer>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4 max-w-3xl text-center">
            <h2 class="text-3xl font-bold text-dark mb-4">{{ __('messages.have_questions') }}</h2>
            <p class="text-lg text-gray-600 mb-8">
                {{ __('messages.contact_phone_whatsapp') }}
            </p>
            <a href="tel:+5978180018" 
               class="inline-flex items-center gap-3 px-8 py-4 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition text-lg">
                <i class="fas fa-phone"></i>
                <span>+597 8180018</span>
            </a>
            <p class="text-sm text-gray-500 mt-4">
                {{ __('messages.also_whatsapp') }}
            </p>
        </div>
    </div>
</x-app-layout>