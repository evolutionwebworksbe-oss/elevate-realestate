<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Categorie Volgorde - Agent Profielen
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-4">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Hoe werkt het?</h3>
                    <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                        <li>Vul voor elke categorie een nummer in (bijvoorbeeld: 1, 2, 3, 4...)</li>
                        <li><strong>Lagere nummers verschijnen eerst</strong> op de agent profiel pagina's</li>
                        <li><strong>Je kunt Te Koop en Te Huur categorieën door elkaar heen mixen!</strong></li>
                        <li>Categorieën met dezelfde nummer worden alfabetisch gesorteerd</li>
                        <li>Dit beïnvloedt <strong>ALLEEN</strong> de volgorde op agent profielen, niet het menu</li>
                    </ul>
                    <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-lightbulb mr-1"></i>
                            <strong>Voorbeeld mix:</strong> Woningen Te Koop (1), Appartementen Te Huur (2), Percelen Te Koop (3), Panden Te Huur (4)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.category-order.update') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Te Koop Categories -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-home text-primary mr-2"></i>
                            Te Koop Categorieën
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">Lagere nummers verschijnen eerst (1, 2, 3...)</p>
                        
                        <div class="space-y-3">
                            @forelse($saleCategories as $key => $category)
                                <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <label class="text-xs text-gray-500 mb-1">Volgorde</label>
                                        <input type="number" 
                                               name="sale_order[{{ $key }}]" 
                                               value="{{ $category['order'] }}"
                                               min="1"
                                               max="99"
                                               placeholder="1"
                                               class="w-20 px-3 py-2 border border-gray-300 rounded text-center font-bold text-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    </div>
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-900 text-lg">{{ $category['title'] }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ $key }})</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Geen categorieën gevonden</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Te Huur Categories -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-key text-green-600 mr-2"></i>
                            Te Huur Categorieën
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">Lagere nummers verschijnen eerst (1, 2, 3...)</p>
                        
                        <div class="space-y-3">
                            @forelse($rentCategories as $key => $category)
                                <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <label class="text-xs text-gray-500 mb-1">Volgorde</label>
                                        <input type="number" 
                                               name="rent_order[{{ $key }}]" 
                                               value="{{ $category['order'] }}"
                                               min="1"
                                               max="99"
                                               placeholder="1"
                                               class="w-20 px-3 py-2 border border-gray-300 rounded text-center font-bold text-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    </div>
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-900 text-lg">{{ $category['title'] }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ $key }})</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Geen categorieën gevonden</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Opslaan
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
