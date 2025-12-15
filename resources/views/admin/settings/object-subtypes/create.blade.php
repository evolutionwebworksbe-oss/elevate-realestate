<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Object Subtype
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.settings.object-subtypes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="objectType_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Object Type
                            </label>
                            <select name="objectType_id" 
                                    id="objectType_id" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <option value="">Select Object Type</option>
                                @foreach($objectTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('objectType_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->naam }}
                                    </option>
                                @endforeach
                            </select>
                            @error('objectType_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="naam" class="block text-sm font-medium text-gray-700 mb-2">
                                Subtype Name
                            </label>
                            <input type="text" 
                                   name="naam" 
                                   id="naam" 
                                   value="{{ old('naam') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('naam')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Subtype
                            </button>
                            <a href="{{ route('admin.settings.object-subtypes.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>