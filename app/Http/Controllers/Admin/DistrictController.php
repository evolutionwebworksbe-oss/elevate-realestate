<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::orderBy('id')->get();
        return view('admin.settings.districts.index', compact('districts'));
    }

    public function create()
    {
        return view('admin.settings.districts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:100|unique:districten,naam',
        ]);

        District::create(['naam' => $request->naam]);

        return redirect()->route('admin.settings.districts.index')
            ->with('success', 'District created successfully');
    }

    public function edit(District $district)
    {
        return view('admin.settings.districts.edit', compact('district'));
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'naam' => 'required|string|max:100|unique:districten,naam,' . $district->id,
        ]);

        $district->update(['naam' => $request->naam]);

        return redirect()->route('admin.settings.districts.index')
            ->with('success', 'District updated successfully');
    }

    public function destroy(District $district)
    {
        $district->delete();

        return redirect()->route('admin.settings.districts.index')
            ->with('success', 'District deleted successfully');
    }
}
