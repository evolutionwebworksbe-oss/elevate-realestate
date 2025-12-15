<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObjectSubType;
use App\Models\ObjectType;
use Illuminate\Http\Request;

class ObjectSubTypeController extends Controller
{
    public function index()
    {
        $subtypes = ObjectSubType::with('objectType')->orderBy('objectType_id')->orderBy('naam')->get();
        return view('admin.settings.object-subtypes.index', compact('subtypes'));
    }

    public function create()
    {
        $objectTypes = ObjectType::orderBy('naam')->get();
        return view('admin.settings.object-subtypes.create', compact('objectTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:100',
            'objectType_id' => 'required|exists:objectTypes,id',
        ]);

        ObjectSubType::create($request->only(['naam', 'objectType_id']));

        return redirect()->route('admin.settings.object-subtypes.index')
            ->with('success', 'Object Subtype created successfully');
    }

    public function edit(ObjectSubType $objectSubtype)
    {
        $objectTypes = ObjectType::orderBy('naam')->get();
        return view('admin.settings.object-subtypes.edit', compact('objectSubtype', 'objectTypes'));
    }

    public function update(Request $request, ObjectSubType $objectSubtype)
    {
        $request->validate([
            'naam' => 'required|string|max:100',
            'objectType_id' => 'required|exists:objectTypes,id',
        ]);

        $objectSubtype->update($request->only(['naam', 'objectType_id']));

        return redirect()->route('admin.settings.object-subtypes.index')
            ->with('success', 'Object Subtype updated successfully');
    }

    public function destroy(ObjectSubType $objectSubtype)
    {
        // Check if any properties use this subtype
        if ($objectSubtype->properties()->count() > 0) {
            return redirect()->route('admin.settings.object-subtypes.index')
                ->with('error', 'Cannot delete: This subtype is used by ' . $objectSubtype->properties()->count() . ' properties');
        }

        $objectSubtype->delete();

        return redirect()->route('admin.settings.object-subtypes.index')
            ->with('success', 'Object Subtype deleted successfully');
    }
}