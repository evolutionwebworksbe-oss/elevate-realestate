<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $team->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.team.edit', $team) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.team.destroy', $team) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this team member?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                <a href="{{ route('admin.team.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-7xl mx-auto">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column - Photo and Basic Info -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            @if($team->image)
                                <img src="{{ asset('portal/' .$team->image) }}" 
                                     alt="{{ $team->name }}"
                                     class="w-full rounded-lg mb-4">
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                    <i class="fas fa-user text-8xl text-gray-400"></i>
                                </div>
                            @endif
                            
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $team->name }}</h3>
                            <div class="text-lg text-gray-600 mb-4">
                                @if($team->titleTypes->count() > 0)
                                    {{ $team->titleTypes->pluck('name')->join(', ') }}
                                @else
                                    No Title
                                @endif
                            </div>
                            
                            @if($team->phone)
                                <div class="flex items-center text-gray-700 mb-2">
                                    <i class="fas fa-phone w-6"></i>
                                    <span>{{ $team->phone }}</span>
                                </div>
                            @endif

                            @if($team->whatsapp)
                                <div class="flex items-center text-gray-700 mb-2">
                                    <i class="fab fa-whatsapp w-6"></i>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $team->whatsapp) }}" 
                                    target="_blank"
                                    class="text-green-600 hover:underline">
                                        {{ $team->whatsapp }}
                                    </a>
                                </div>
                            @endif
                                                        
                            @if($team->email)
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-envelope w-6"></i>
                                    <a href="mailto:{{ $team->email }}" class="text-blue-500 hover:underline">
                                        {{ $team->email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Description and Properties -->
                <div class="md:col-span-2">
                    <!-- Description -->
                    @if($team->description)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-3 text-gray-800 border-b pb-2">
                                    <i class="fas fa-info-circle"></i> Description
                                </h3>
                                <p class="text-gray-700 whitespace-pre-line">{{ $team->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Assigned Properties -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                                <i class="fas fa-home"></i> Assigned Properties ({{ $team->properties->count() }})
                            </h3>
                            
                            @if($team->properties->count() > 0)
                                <div class="space-y-3">
                                    @foreach($team->properties as $property)
                                        <a href="{{ route('admin.properties.show', $property) }}" 
                                           class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                                            <div class="flex items-center gap-4">
                                                @if($property->featuredFoto)
                                                    <img src="{{ asset('portal/'.$property->featuredFoto) }}" 
                                                         alt="{{ $property->naam }}"
                                                         class="w-20 h-20 object-cover rounded">
                                                @else
                                                    <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-home text-2xl text-gray-400"></i>
                                                    </div>
                                                @endif
                                                
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900">{{ $property->naam }}</h4>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $property->objectSubType->naam ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $property->district->naam ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                
                                                <div class="text-right">
                                                    <p class="font-semibold text-gray-900">
                                                        {{ $property->currencyRelation->name ?? '' }} {{ number_format($property->vraagPrijs, 0) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No properties assigned yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>