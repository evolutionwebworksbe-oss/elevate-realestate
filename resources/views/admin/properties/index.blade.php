<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objecten
            </h2>
            <a href="{{ route('admin.properties.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Object Toevoegen</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        <!-- Search and Filters -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.properties.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Zoeken</label>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Zoek op naam of ID..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" 
                                    id="status" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Alle Statussen</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Beschikbaar</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Verkocht/Verhuurd</option>
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Gereserveerd</option>
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select name="type" 
                                    id="type" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Alle Types</option>
                                @foreach(\App\Models\ObjectType::orderBy('naam')->get() as $type)
                                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->naam }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- District Filter -->
                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-2">District</label>
                            <select name="district" 
                                    id="district" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Alle Districten</option>
                                @foreach(\App\Models\District::orderBy('naam')->get() as $district)
                                    <option value="{{ $district->id }}" {{ request('district') == $district->id ? 'selected' : '' }}>
                                        {{ $district->naam }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Min Prijs</label>
                            <input type="number" 
                                   name="min_price" 
                                   id="min_price" 
                                   value="{{ request('min_price') }}"
                                   placeholder="0"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Max Prijs</label>
                            <input type="number" 
                                   name="max_price" 
                                   id="max_price" 
                                   value="{{ request('max_price') }}"
                                   placeholder="Onbeperkt"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Buttons -->
                        <div class="md:col-span-2 flex items-end gap-2">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-search"></i> Zoeken
                            </button>
                            <a href="{{ route('admin.properties.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($properties->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prijs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($properties as $property)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $property->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($property->featuredFoto)
                                        <img src="{{ asset('portal' . $property->featuredFoto) }}" 
                                             alt="{{ $property->naam }}" 
                                             class="w-20 h-15 object-cover rounded">
                                    @else
                                        <div class="w-20 h-15 bg-gray-200 rounded"></div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $property->naam }}</div>
                                    <div class="text-sm text-gray-500">{{ $property->district->naam ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $property->objectSubType->naam ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($property->discount && $property->discount > 0)
                                            <span class="line-through text-gray-400">
                                                {{ number_format($property->vraagPrijs) }}
                                            </span>
                                            <br>
                                            <span class="font-bold text-red-600">{{ number_format($property->discount) }}</span>
                                        @else
                                            {{ number_format($property->vraagPrijs) }}
                                        @endif
                                        <span class="text-xs text-gray-500">{{ $property->currencyRelation?->name ?? $property->currency }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($property->status == 1)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Beschikbaar
                                        </span>
                                    @elseif($property->status == 2)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Verkocht/Verhuurd
                                        </span>
                                    @elseif($property->status == 3)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Gereserveerd
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.properties.show', $property) }}" 
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Bekijken">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.properties.edit', $property) }}" 
                                           class="text-green-600 hover:text-green-900"
                                           title="Bewerken">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.properties.destroy', $property) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Weet u zeker dat u dit object wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Verwijderen">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-600">Geen objecten gevonden.</p>
                @if(request()->hasAny(['search', 'status', 'type', 'district', 'min_price', 'max_price']))
                    <a href="{{ route('admin.properties.index') }}" 
                       class="text-blue-500 hover:text-blue-700 mt-2 inline-block">
                        Reset filters om alle objecten te zien
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-admin-layout>