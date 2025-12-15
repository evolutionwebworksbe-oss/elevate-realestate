<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObjectType;
use Illuminate\Http\Request;

class ObjectTypeController extends Controller
{
    public function index()
    {
        $objecttypes = ObjectType::orderBy('id')->get();
        return view('admin.settings.object-types.index', compact('objecttypes'));
    }

    public function create()
    {
        return view('admin.settings.object-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:50|unique:objectTypes,naam',
        ]);

        ObjectType::create(['naam' => $request->naam]);

        return redirect()->route('admin.settings.object-types.index')
            ->with('success', 'Object type created successfully');
    }

    public function edit(ObjectType $objectType)
    {
        return view('admin.settings.object-types.edit', compact('objectType'));
    }

    public function update(Request $request, ObjectType $objectType)
    {
        $request->validate([
            'naam' => 'required|string|max:50|unique:objectTypes,naam,' . $objectType->id,
        ]);

        $objectType->update(['naam' => $request->naam]);

        return redirect()->route('admin.settings.object-types.index')
            ->with('success', 'Object type updated successfully');
    }

    public function destroy(ObjectType $objectType)
    {
        $objectType->delete();

        return redirect()->route('admin.settings.object-types.index')
            ->with('success', 'Object type deleted successfully');
    }
}
