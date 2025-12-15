<div class="bg-white border rounded-lg p-4 mb-2" style="margin-left: {{ $level * 30 }}px">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4 flex-1">
            <div class="cursor-move text-gray-400">
                <i class="fas fa-grip-vertical"></i>
            </div>
            
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    @if($item->icon)
                        <i class="{{ $item->icon }} text-gray-600"></i>
                    @endif
                    <span class="font-semibold text-gray-900">{{ $item->title }}</span>
                    @if($item->title_en)
                        <span class="text-sm text-gray-500">({{ $item->title_en }})</span>
                    @endif
                    @if(!$item->is_active)
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactive</span>
                    @endif
                </div>
                
                <div class="text-sm text-gray-500 mt-1">
                    @if($item->route_name)
                        <span class="inline-flex items-center">
                            <i class="fas fa-route mr-1"></i> Route: {{ $item->route_name }}
                            @if($item->route_params)
                                <span class="ml-2 text-xs bg-gray-100 px-2 py-0.5 rounded">{{ json_encode($item->route_params) }}</span>
                            @endif
                        </span>
                    @elseif($item->url)
                        <span class="inline-flex items-center">
                            <i class="fas fa-link mr-1"></i> URL: {{ $item->url }}
                        </span>
                    @else
                        <span class="text-gray-400">No link configured</span>
                    @endif
                    
                    @if($item->target === '_blank')
                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">New Window</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openEditItemModal({{ $item->id }})" class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded hover:bg-blue-50">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button onclick="deleteItem({{ $item->id }})" class="text-red-600 hover:text-red-800 px-3 py-1 rounded hover:bg-red-50">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>

@if($item->children->count() > 0)
    @foreach($item->children as $child)
        @include('admin.menus.partials.menu-item', ['item' => $child, 'level' => $level + 1])
    @endforeach
@endif
