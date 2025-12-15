<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $user->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-6">
        <div class="max-w-4xl mx-auto">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- User Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                        <i class="fas fa-user"></i> User Information
                    </h3>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="font-medium text-gray-700">ID</dt>
                            <dd class="text-gray-900">{{ $user->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-gray-700">Name</dt>
                            <dd class="text-gray-900">{{ $user->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-gray-700">Email</dt>
                            <dd class="text-gray-900">{{ $user->email }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-gray-700">Role</dt>
                            <dd>
                                @if($user->role === 'super_admin')
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Super Admin
                                    </span>
                                @elseif($user->role === 'admin')
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Admin
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Employee
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-gray-700">Created At</dt>
                            <dd class="text-gray-900">{{ $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="font-medium text-gray-700">Last Updated</dt>
                            <dd class="text-gray-900">{{ $user->updated_at ? $user->updated_at->format('M d, Y H:i') : 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Permissions Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                        <i class="fas fa-shield-alt"></i> Permissions
                    </h3>
                    
                    <div class="space-y-2">
                        @if($user->isSuperAdmin())
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Full system access</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Can manage all users</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Can manage all properties</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Can manage all settings</span>
                            </div>
                        @elseif($user->isAdmin())
                            <div class="flex items-center text-blue-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Can manage users</span>
                            </div>
                            <div class="flex items-center text-blue-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Can manage properties</span>
                            </div>
                            <div class="flex items-center text-blue-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Can manage settings</span>
                            </div>
                        @else
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>Can view and edit assigned properties</span>
                            </div>
                            <div class="flex items-center text-gray-400">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span>Cannot manage users</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>