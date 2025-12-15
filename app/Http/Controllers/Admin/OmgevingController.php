<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Omgeving;
use App\Models\District;
use Illuminate\Http\Request;

class OmgevingController extends Controller
{
    public function index()
    {
        // Load districts with each omgeving for display
        $omgevingen = Omgeving::with('district')->orderBy('district_id')->orderBy('naam')->get();
        return view('admin.settings.omgevingen.index', compact('omgevingen'));
    }

    public function create()
    {
        // Get all districts for the dropdown
        $districts = District::orderBy('naam')->get();
        return view('admin.settings.omgevingen.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:100',
            'district_id' => 'required|exists:districten,id',
        ]);

        Omgeving::create($request->only(['naam', 'district_id']));

        return redirect()->route('admin.settings.omgevingen.index')
            ->with('success', 'Omgeving created successfully');
    }

    public function edit(Omgeving $omgevingen)
    {
        // Get all districts for the dropdown
        $districts = District::orderBy('naam')->get();
        return view('admin.settings.omgevingen.edit', compact('omgevingen', 'districts'));
    }

    public function update(Request $request, Omgeving $omgevingen)
    {
        $request->validate([
            'naam' => 'required|string|max:100',
            'district_id' => 'required|exists:districten,id',
        ]);

        $omgevingen->update($request->only(['naam', 'district_id']));

        return redirect()->route('admin.settings.omgevingen.index')
            ->with('success', 'Omgeving updated successfully');
    }

    public function destroy(Omgeving $omgevingen)
    {
        // Check if any properties use this omgeving
        if ($omgevingen->properties()->count() > 0) {
            return redirect()->route('admin.settings.omgevingen.index')
                ->with('error', 'Cannot delete: This omgeving is used by ' . $omgevingen->properties()->count() . ' properties');
        }

        $omgevingen->delete();

        return redirect()->route('admin.settings.omgevingen.index')
            ->with('success', 'Omgeving deleted successfully');
    }
}