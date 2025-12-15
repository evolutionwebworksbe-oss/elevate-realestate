<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->naam }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.properties.edit', $property) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit"></i> Bewerken
                </a>
                <a href="{{ route('admin.properties.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Terug
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- Featured Image -->
            @if($property->featuredFoto)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <img src="{{ asset('portal/'. $property->featuredFoto) }}" 
                         alt="{{ $property->naam }}" 
                         class="w-full h-96 object-cover">
                </div>
            @endif

            <!-- Basic Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                        Basis Informatie
                    </h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="font-medium text-gray-700">Type</dt>
                            <dd class="text-gray-900">{{ $property->objectSubType->naam ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">Prijs</dt>
                            <dd class="text-gray-900">{{ $property->currencyRelation->name ?? '' }} {{ number_format($property->vraagPrijs, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">Locatie</dt>
                            <dd class="text-gray-900">{{ $property->district->naam ?? 'N/A' }}{{ $property->omgeving ? ', ' . $property->omgeving->naam : '' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-700">Status</dt>
                            <dd class="text-gray-900">
                                @if($property->status == 1) Beschikbaar
                                @elseif($property->status == 2) Verkocht/Verhuurd
                                @else Gereserveerd
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Team Members -->
            @if($property->teamMembers->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-users"></i> Toegewezen Teamleden
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($property->teamMembers as $member)
                                <div class="border rounded-lg p-4">
                                    <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->titleType->name ?? 'Geen titel' }}</div>
                                    @if($member->phone)
                                        <div class="text-sm text-gray-600 mt-2">
                                            <i class="fas fa-phone"></i> {{ $member->phone }}
                                        </div>
                                    @endif
                                    @if($member->email)
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-envelope"></i> {{ $member->email }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Gallery Images -->
            @if($property->images->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                            <i class="fas fa-images"></i> Foto Galerij
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($property->images as $image)
                                <div class="aspect-square bg-cover bg-center rounded-lg" 
                                     style="background-image: url('{{ asset('portal/'.$image->url) }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-admin-layout>