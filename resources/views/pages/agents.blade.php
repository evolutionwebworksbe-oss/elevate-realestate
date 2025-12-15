<x-app-layout>
    <x-slot name="title">{{ __('messages.our_agents') }} - Elevate Real Estate</x-slot>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-primary-dark to-primary text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                {{ __('messages.our_agents') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 max-w-4xl mx-auto">
                {{ __('messages.meet_our_team_desc') }}
            </p>
        </div>
    </div>

    <!-- Agents Grid -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 max-w-7xl">
            
            @if($teamMembers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($teamMembers as $member)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group">
                            <!-- Agent Photo -->
                            <div class="relative h-80 overflow-hidden bg-gray-200">
                                @if($member->image)
                                    <img src="{{ asset('portal/' . $member->image) }}" 
                                         alt="{{ $member->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary-dark">
                                        <i class="fas fa-user text-white text-6xl"></i>
                                    </div>
                                @endif
                                
                                <!-- Overlay with Contact Info -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                    <div class="text-white space-y-2 w-full">
                                        @if($member->phone)
                                            <a href="tel:{{ $member->phone }}" class="flex items-center gap-2 hover:text-accent transition">
                                                <i class="fas fa-phone"></i>
                                                <span>{{ $member->phone }}</span>
                                            </a>
                                        @endif
                                        @if($member->email)
                                            <a href="mailto:{{ $member->email }}" class="flex items-center gap-2 hover:text-accent transition">
                                                <i class="fas fa-envelope"></i>
                                                <span class="text-sm">{{ $member->email }}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Agent Info -->
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-dark mb-2">{{ $member->name }}</h3>
                                
                                @if($member->titleTypes && $member->titleTypes->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($member->titleTypes as $title)
                                            <span class="px-3 py-1 bg-primary/10 text-primary text-sm rounded-full">
                                                {{ app()->getLocale() == 'en' && $title->name_en ? $title->name_en : $title->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($member->bio)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ app()->getLocale() == 'en' && $member->bio_en ? $member->bio_en : $member->bio }}
                                    </p>
                                @endif

                                <!-- Property Count -->
                                @if($member->properties_count > 0)
                                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                                        <i class="fas fa-home"></i>
                                        <span>{{ $member->properties_count }} {{ __('messages.active_properties') }}</span>
                                    </div>
                                @endif

                                <!-- View Profile Button -->
                                <a href="{{ route('team.profile', $member) }}" 
                                   class="block w-full text-center bg-primary hover:bg-primary-dark text-white py-3 rounded-lg transition font-medium">
                                    {{ __('messages.view_profile') }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">{{ __('messages.no_team_members') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-gradient-to-br from-primary to-primary-dark text-white py-16">
        <div class="container mx-auto px-4 text-center max-w-4xl">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                {{ __('messages.ready_to_find_home') }}
            </h2>
            <p class="text-xl text-gray-200 mb-8">
                {{ __('messages.agents_ready_to_help') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('properties.sale') }}" 
                   class="bg-accent hover:bg-opacity-90 text-primary-dark font-bold py-4 px-8 rounded-lg transition inline-flex items-center justify-center">
                    {{ __('messages.browse_properties') }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="{{ route('contact') }}" 
                   class="bg-white hover:bg-gray-100 text-primary-dark font-bold py-4 px-8 rounded-lg transition inline-flex items-center justify-center">
                    {{ __('messages.contact_us') }}
                    <i class="fas fa-envelope ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
