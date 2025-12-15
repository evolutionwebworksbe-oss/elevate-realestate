<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Omgeving
        </h2>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.settings.omgevingen.update', $omgevingen) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="district_id" class="block text-sm font-medium text-gray-700 mb-2">
                                District
                            </label>
                            <select name="district_id" 
                                    id="district_id" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <option value="">Select District</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" 
                                            {{ old('district_id', $omgevingen->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->naam }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="naam" class="block text-sm font-medium text-gray-700 mb-2">
                                Omgeving Name
                            </label>
                            <input type="text" 
                                   name="naam" 
                                   id="naam" 
                                   value="{{ old('naam', $omgevingen->naam) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('naam')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Omgeving
                            </button>
                            <a href="{{ route('admin.settings.omgevingen.index') }}" 
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