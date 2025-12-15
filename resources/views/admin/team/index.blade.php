<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Team Leden
            </h2>
            <a href="{{ route('admin.team.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus"></i> Nieuw Teamlid
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-7xl mx-auto">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($teamMembers as $member)
                            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                @if($member->image)
                                    <img src="{{ asset('portal/' . $member->image) }}" 
                                         alt="{{ $member->name }}"
                                         class="w-full h-48 object-cover rounded-lg mb-4">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                        <i class="fas fa-user text-6xl text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <h3 class="font-bold text-lg text-gray-900">{{ $member->name }}</h3>
                                <div class="text-sm text-gray-600 mb-2">
                                    @if($member->titleTypes->count() > 0)
                                        {{ $member->titleTypes->pluck('name')->join(', ') }}
                                    @else
                                        Geen titel
                                    @endif
                                </div>
                                
                                @if($member->phone)
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-phone"></i> {{ $member->phone }}
                                    </p>
                                @endif

                                @if($member->whatsapp)
                                    <p class="text-sm text-gray-500">
                                        <i class="fab fa-whatsapp text-green-600"></i> {{ $member->whatsapp }}
                                    </p>
                                @endif  
                                                              
                                @if($member->email)
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-envelope"></i> {{ $member->email }}
                                    </p>
                                @endif
                                
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('admin.team.show', $member) }}" 
                                       class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm">
                                        <i class="fas fa-eye"></i> Bekijken
                                    </a>
                                    <a href="{{ route('admin.team.edit', $member) }}" 
                                       class="flex-1 bg-green-500 hover:bg-green-700 text-white text-center py-2 px-3 rounded text-sm">
                                        <i class="fas fa-edit"></i> Bewerken
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg mb-4">Geen teamleden gevonden</p>
                                <a href="{{ route('admin.team.create') }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-plus"></i> Voeg eerste teamlid toe
                                </a>
                            </div>
                        @endforelse
                    </div>
                    
                    @if($teamMembers->hasPages())
                        <div class="mt-6">
                            {{ $teamMembers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>