<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Menu: {{ $menu->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Location: <span class="font-semibold">{{ $menu->location }}</span></p>
                        </div>
                        <div class="space-x-2">
                            <button onclick="openAddItemModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                <i class="fas fa-plus mr-2"></i>Add Menu Item
                            </button>
                            <a href="{{ route('admin.menus.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded inline-block">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Menu Items List -->
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h3 class="text-lg font-semibold mb-4">Menu Structure</h3>
                        
                        @if($menu->items->count() > 0)
                            <div id="menu-items" class="space-y-2">
                                @foreach($menu->items as $item)
                                    @include('admin.menus.partials.menu-item', ['item' => $item, 'level' => 0])
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No menu items yet. Click "Add Menu Item" to get started.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Item Modal -->
    <div id="itemModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-xl font-bold">Add Menu Item</h3>
                <button onclick="closeItemModal()" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="itemForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title (Dutch)</label>
                        <input type="text" name="title" id="itemTitle" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title (English)</label>
                        <input type="text" name="title_en" id="itemTitleEn" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Item (leave empty for top-level)</label>
                    <select name="parent_id" id="itemParent" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">None (Top Level)</option>
                        @foreach($menu->items as $parentItem)
                            <option value="{{ $parentItem->id }}">{{ $parentItem->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Link Type</label>
                    <div class="flex gap-4 mb-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="link_type" value="route" checked onchange="toggleLinkType('route')" class="mr-2">
                            <span>Route Name</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="link_type" value="url" onchange="toggleLinkType('url')" class="mr-2">
                            <span>Custom URL</span>
                        </label>
                    </div>

                    <div id="routeField">
                        <select name="route_name" id="itemRoute" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select Route</option>
                            @foreach($availableRoutes as $route => $label)
                                <option value="{{ $route }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Select a predefined route</p>
                    </div>

                    <div id="urlField" class="hidden">
                        <input type="text" name="url" id="itemUrl" placeholder="https://example.com" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <p class="text-xs text-gray-500 mt-1">Enter full URL including https://</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Route Parameters (JSON) - Optional</label>
                    <textarea name="route_params" id="itemRouteParams" rows="2" placeholder='{"object_subtype": "woningen"}' class="w-full px-3 py-2 border border-gray-300 rounded-md font-mono text-sm"></textarea>
                    <p class="text-xs text-gray-500 mt-1">For routes with parameters, enter as JSON object</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                        <select name="target" id="itemTarget" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="_self">Same Window</option>
                            <option value="_blank">New Window</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon (Font Awesome)</label>
                        <input type="text" name="icon" id="itemIcon" placeholder="fas fa-home" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                        <input type="number" name="order" id="itemOrder" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="itemActive" value="1" checked class="rounded border-gray-300 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeItemModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleLinkType(type) {
            const routeField = document.getElementById('routeField');
            const urlField = document.getElementById('urlField');
            const routeInput = document.getElementById('itemRoute');
            const urlInput = document.getElementById('itemUrl');

            if (type === 'route') {
                routeField.classList.remove('hidden');
                urlField.classList.add('hidden');
                urlInput.value = '';
            } else {
                routeField.classList.add('hidden');
                urlField.classList.remove('hidden');
                routeInput.value = '';
            }
        }

        function openAddItemModal() {
            document.getElementById('modalTitle').textContent = 'Add Menu Item';
            document.getElementById('itemForm').action = "{{ route('admin.menus.items.store', $menu) }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('itemForm').reset();
            document.getElementById('itemActive').checked = true;
            document.getElementById('itemModal').classList.remove('hidden');
        }

        function openEditItemModal(itemId) {
            fetch(`/admin/menus/{{ $menu->id }}/items/${itemId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Edit Menu Item';
                    document.getElementById('itemForm').action = `/admin/menus/{{ $menu->id }}/items/${itemId}`;
                    document.getElementById('formMethod').value = 'PUT';
                    
                    document.getElementById('itemTitle').value = data.title || '';
                    document.getElementById('itemTitleEn').value = data.title_en || '';
                    document.getElementById('itemParent').value = data.parent_id || '';
                    document.getElementById('itemTarget').value = data.target || '_self';
                    document.getElementById('itemIcon').value = data.icon || '';
                    document.getElementById('itemOrder').value = data.order || 0;
                    document.getElementById('itemActive').checked = data.is_active;
                    document.getElementById('itemRouteParams').value = data.route_params ? JSON.stringify(data.route_params) : '';

                    if (data.url) {
                        document.querySelector('input[name="link_type"][value="url"]').checked = true;
                        toggleLinkType('url');
                        document.getElementById('itemUrl').value = data.url;
                    } else {
                        document.querySelector('input[name="link_type"][value="route"]').checked = true;
                        toggleLinkType('route');
                        document.getElementById('itemRoute').value = data.route_name || '';
                    }

                    document.getElementById('itemModal').classList.remove('hidden');
                });
        }

        function closeItemModal() {
            document.getElementById('itemModal').classList.add('hidden');
        }

        function deleteItem(itemId) {
            if (confirm('Are you sure you want to delete this menu item?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/menus/{{ $menu->id }}/items/${itemId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    @endpush
</x-admin-layout>
