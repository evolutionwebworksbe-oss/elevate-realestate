<x-app-layout>
    <x-slot name="title">{{ __('messages.advertise_title') }} - Elevate Real Estate</x-slot>

    <!-- Hero Banner -->
    <div class="bg-gradient-to-r from-primary to-primary-dark text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                {{ __('messages.advertise_header') }}
            </h1>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 max-w-4xl">
        
        <!-- Info Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <p class="text-lg text-gray-700 leading-relaxed mb-6">
                {{ __('messages.advertise_intro') }}
            </p>
        </div>

        <!-- Package Details -->
        <div class="bg-light rounded-2xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.ad_package') }}</h2>
            <p class="text-3xl font-bold text-primary mb-6">€105,-</p>
            
            <h3 class="font-bold text-dark mb-3">{{ __('messages.you_receive') }}</h3>
            <ul class="space-y-3 mb-6">
                <li class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-accent text-xl mt-1"></i>
                    <span class="text-gray-700">{{ __('messages.ad_benefit_1') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-accent text-xl mt-1"></i>
                    <span class="text-gray-700">{{ __('messages.ad_benefit_2') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-accent text-xl mt-1"></i>
                    <span class="text-gray-700">{{ __('messages.ad_benefit_3') }}</span>
                </li>
            </ul>

            <div class="bg-white rounded-xl p-6">
                <h4 class="font-bold text-dark mb-2">{{ __('messages.professional_photos') }}</h4>
                <p class="text-gray-700">
                    {{ __('messages.professional_photos_desc') }}
                </p>
            </div>
        </div>

        <!-- Requirements -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-dark mb-4">{{ __('messages.what_you_need') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('messages.send_via') }}
            </p>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-start gap-3">
                    <i class="fas fa-id-card text-primary text-xl mt-1"></i>
                    <span class="text-gray-700">{{ __('messages.requirement_id') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-file-alt text-primary text-xl mt-1"></i>
                    <span class="text-gray-700">{{ __('messages.requirement_info') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-camera text-primary text-xl mt-1"></i>
                    <span class="text-gray-700">{{ __('messages.requirement_photos') }}</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-calendar-check text-primary text-xl mt-1"></i>
                    <span class="text-gray-700">{{ __('messages.requirement_schedule') }}</span>
                </li>
            </ul>

            <div class="bg-light rounded-xl p-6">
                <p class="text-gray-700">
                    {{ __('messages.upload_process') }}
                </p>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-8 text-white text-center">
            <h2 class="text-2xl font-bold mb-4">{{ __('messages.send_now_via') }}</h2>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/5978163449?text={{ urlencode(__('messages.advertise_whatsapp_text')) }}" 
                   target="_blank"
                   class="px-8 py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold flex items-center justify-center gap-3 transition">
                    <i class="fab fa-whatsapp text-2xl"></i>
                    <span>WhatsApp</span>
                </a>
                <a href="mailto:marketing@elevaterealestate.sr" 
                   class="px-8 py-4 bg-white hover:bg-gray-100 text-primary rounded-xl font-semibold flex items-center justify-center gap-3 transition">
                    <i class="fas fa-envelope text-2xl"></i>
                    <span>Email</span>
                </a>
            </div>
            <p class="mt-6 text-sm text-gray-200">
                {{ __('messages.more_questions_call') }}
            </p>
        </div>
    </div>
</x-app-layout>