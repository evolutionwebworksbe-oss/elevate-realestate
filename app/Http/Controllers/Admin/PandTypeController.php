<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PandType;
use Illuminate\Http\Request;

class PandTypeController extends Controller
{
    public function index()
    {
        $pandtypes = PandType::orderBy('id')->get();
        return view('admin.settings.pand-types.index', compact('pandtypes'));
    }

    public function create()
    {
        return view('admin.settings.pand-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:50|unique:pandTypes,naam',
        ]);

        PandType::create(['naam' => $request->naam]);

        return redirect()->route('admin.settings.pand-types.index')
            ->with('success', 'Pand Type created successfully');
    }

    public function edit(PandType $pandType)
    {
        return view('admin.settings.pand-types.edit', compact('pandType'));
    }

    public function update(Request $request, PandType $pandType)
    {
        $request->validate([
            'naam' => 'required|string|max:50|unique:pandTypes,naam,' . $pandType->id,
        ]);

        $pandType->update(['naam' => $request->naam]);

        return redirect()->route('admin.settings.pand-types.index')
            ->with('success', 'Pand Type updated successfully');
    }

    public function destroy(PandType $pandType)
    {
        $pandType->delete();

        return redirect()->route('admin.settings.pand-types.index')
            ->with('success', 'Pand Type deleted successfully');
    }
}
