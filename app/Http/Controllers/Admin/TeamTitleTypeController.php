<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamTitleType;
use Illuminate\Http\Request;

class TeamTitleTypeController extends Controller
{
    public function index()
    {
        $titletypes = TeamTitleType::orderBy('id')->get();
        return view('admin.settings.team-titletypes.index', compact('titletypes'));
    }

    public function create()
    {
        return view('admin.settings.team-titletypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:team_title_type,name',
        ]);

        TeamTitleType::create(['name' => $request->name]);

        return redirect()->route('admin.settings.team-titletypes.index')
            ->with('success', 'Title type created successfully');
    }

    // Changed parameter name to match everywhere
    public function edit(TeamTitleType $teamTitletype)
    {
        return view('admin.settings.team-titletypes.edit', compact('teamTitletype'));
    }

    // Keep consistent parameter name
    public function update(Request $request, TeamTitleType $teamTitletype)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:team_title_type,name,' . $teamTitletype->id,
        ]);

        $teamTitletype->update(['name' => $request->name]);

        return redirect()->route('admin.settings.team-titletypes.index')
            ->with('success', 'Title type updated successfully');
    }

    // Keep consistent parameter name
    public function destroy(TeamTitleType $teamTitletype)
    {
        $teamTitletype->delete();

        return redirect()->route('admin.settings.team-titletypes.index')
            ->with('success', 'Title type deleted successfully');
    }
}