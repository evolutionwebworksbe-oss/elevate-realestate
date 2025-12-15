<x-app-layout>
    <x-slot name="title">{{ __('messages.contact') }} - Elevate Real Estate</x-slot>

    <!-- Hero -->
    <div class="bg-gradient-to-br from-primary-dark to-primary text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('messages.contact_header') }}</h1>
            <p class="text-xl text-gray-200">{{ __('messages.contact_subheader') }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-dark mb-6">{{ __('messages.send_message') }}</h2>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.name') }} *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.email') }} *</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.phone') }}</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.subject') }} *</label>
                        <input type="text" 
                               name="subject" 
                               id="subject" 
                               value="{{ old('subject') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.message') }} *</label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="6" 
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> {{ __('messages.send_message_btn') }}
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-6">
                
                <!-- Contact Details -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-dark mb-6">{{ __('messages.contact_info') }}</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-1">{{ __('messages.address') }}</h3>
                                <p class="text-gray-600">
                                    Frederik Derbystraat no.78<br>
                                    Paramaribo, Suriname
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-1">{{ __('messages.phone') }}</h3>
                                <a href="tel:+597404546" class="text-gray-600 hover:text-primary transition">
                                    +597 404546
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-whatsapp text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-1">WhatsApp</h3>
                                <a href="https://wa.me/5978180018" target="_blank" class="text-gray-600 hover:text-primary transition">
                                    +597 8180018
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-dark mb-1">E-mail</h3>
                                <a href="mailto:info@elevaterealestate.sr" class="text-gray-600 hover:text-primary transition">
                                    info@elevaterealestate.sr
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative z-0">
                    <div id="contact-map" class="h-80 relative z-0"></div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-dark mb-4">{{ __('messages.follow_us') }}</h2>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f text-white text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-pink-600 rounded-lg flex items-center justify-center hover:bg-pink-700 transition">
                            <i class="fab fa-instagram text-white text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center hover:bg-green-700 transition">
                            <i class="fab fa-whatsapp text-white text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('contact-map').setView([5.8520, -55.2038], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            L.marker([5.8520, -55.2038]).addTo(map)
                .bindPopup('Elevate Real Estate').openPopup();
        });
    </script>
    @endpush
</x-app-layout>