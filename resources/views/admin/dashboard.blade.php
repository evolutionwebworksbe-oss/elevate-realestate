<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">Welkom terug, {{ Auth::user()->name }}!</p>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar"></i> {{ date('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        
        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Properties -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg text-white">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Totaal Objecten</p>
                            <p class="text-3xl font-bold mt-2">{{ $propertiesCount }}</p>
                            <p class="text-blue-100 text-xs mt-2">
                                <i class="fas fa-plus-circle"></i> {{ $propertiesThisMonth }} deze maand
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-full">
                            <i class="fas fa-building text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Listings -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg text-white">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Actieve Listings</p>
                            <p class="text-3xl font-bold mt-2">{{ $activeListings }}</p>
                            <p class="text-green-100 text-xs mt-2">
                                <i class="fas fa-home"></i> Beschikbaar
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-full">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-lg text-white">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Team Leden</p>
                            <p class="text-3xl font-bold mt-2">{{ $teamMembersCount }}</p>
                            <p class="text-purple-100 text-xs mt-2">
                                <i class="fas fa-user-friends"></i> Actieve makelaars
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-full">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Subscribers -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 overflow-hidden shadow-lg rounded-lg text-white">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Nieuwsbrief</p>
                            <p class="text-3xl font-bold mt-2">{{ $newsletterCount }}</p>
                            <p class="text-orange-100 text-xs mt-2">
                                <i class="fas fa-envelope"></i> Abonnees
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-full">
                            <i class="fas fa-bell text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
            <!-- For Sale -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <p class="text-gray-600 text-xs font-medium">Te Koop</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $propertiesForSale }}</p>
            </div>

            <!-- For Rent -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                <p class="text-gray-600 text-xs font-medium">Te Huur</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $propertiesForRent }}</p>
            </div>

            <!-- Sold/Rented -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-500">
                <p class="text-gray-600 text-xs font-medium">Verkocht/Verhuurd</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $soldRentedProperties }}</p>
            </div>

            <!-- Reserved -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
                <p class="text-gray-600 text-xs font-medium">Gereserveerd</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $reservedProperties }}</p>
            </div>

            <!-- Featured -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-purple-500">
                <p class="text-gray-600 text-xs font-medium">Uitgelicht</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $featuredCount }}</p>
            </div>

            <!-- Districts -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-pink-500">
                <p class="text-gray-600 text-xs font-medium">Districten</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $districtsCount }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Properties -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-clock text-blue-500"></i> Recente Objecten
                            </h3>
                            <a href="{{ route('admin.properties.index') }}" class="text-sm text-blue-500 hover:text-blue-700">
                                Bekijk Alle <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentProperties as $property)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-lg bg-cover bg-center flex-shrink-0" 
                                         style="background-image: url('{{ $property->featuredFoto ? asset('portal/'.$property->featuredFoto) : asset('portal/img/geenfoto.jpg') }}')">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">{{ $property->naam }}</p>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i> 
                                            {{ $property->district->naam ?? 'N/A' }} • 
                                            {{ $property->objectSubType->naam ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-calendar"></i> {{ $property->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">
                                            {{ $property->currencyRelation->symbol ?? '$' }} 
                                            {{ number_format($property->vraagPrijs, 0, ',', '.') }}
                                        </p>
                                        @if($property->status == 1)
                                            <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">
                                                Beschikbaar
                                            </span>
                                        @elseif($property->status == 2)
                                            <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded">
                                                Verkocht
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded">
                                                Gereserveerd
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.properties.edit', $property) }}" 
                                       class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Nog geen objecten toegevoegd</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-bolt text-yellow-500"></i> Snelle Acties
                        </h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('admin.properties.create') }}" 
                           class="block w-full bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition text-center font-medium">
                            <i class="fas fa-plus-circle"></i> Nieuw Object
                        </a>
                        <a href="{{ route('admin.properties.index') }}" 
                           class="block w-full bg-white hover:bg-gray-50 text-gray-700 p-3 rounded-lg border-2 border-gray-200 transition text-center font-medium">
                            <i class="fas fa-building"></i> Beheer Objecten
                        </a>
                        <a href="{{ route('admin.team.index') }}" 
                           class="block w-full bg-white hover:bg-gray-50 text-gray-700 p-3 rounded-lg border-2 border-gray-200 transition text-center font-medium">
                            <i class="fas fa-users"></i> Beheer Team
                        </a>
                        <a href="{{ route('admin.sliders.index') }}" 
                           class="block w-full bg-white hover:bg-gray-50 text-gray-700 p-3 rounded-lg border-2 border-gray-200 transition text-center font-medium">
                            <i class="fas fa-images"></i> Beheer Sliders
                        </a>
                        <a href="{{ route('admin.newsletters.index') }}" 
                           class="block w-full bg-white hover:bg-gray-50 text-gray-700 p-3 rounded-lg border-2 border-gray-200 transition text-center font-medium">
                            <i class="fas fa-envelope"></i> Nieuwsbrief
                        </a>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="block w-full bg-white hover:bg-gray-50 text-gray-700 p-3 rounded-lg border-2 border-gray-200 transition text-center font-medium">
                            <i class="fas fa-cog"></i> Instellingen
                        </a>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-chart-line text-green-500"></i> Systeem Status
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-images text-blue-500"></i> Totaal Afbeeldingen
                                </span>
                                <span class="font-bold text-gray-900">{{ $totalImages }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-chart-bar text-purple-500"></i> Conversie Rate
                                </span>
                                <span class="font-bold text-gray-900">
                                    {{ $propertiesCount > 0 ? round(($soldRentedProperties / $propertiesCount) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-star text-yellow-500"></i> Featured Rate
                                </span>
                                <span class="font-bold text-gray-900">
                                    {{ $activeListings > 0 ? round(($featuredCount / $activeListings) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
