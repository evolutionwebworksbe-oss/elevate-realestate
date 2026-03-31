<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $projectTypes = ProjectType::withCount('projects')->orderBy('name')->get();
        return view('admin.settings.project-types.index', compact('projectTypes'));
    }

    public function create()
    {
        return view('admin.settings.project-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100|unique:project_types,name',
            'name_en' => 'nullable|string|max:100',
        ]);

        ProjectType::create($request->only('name', 'name_en'));

        return redirect()->route('admin.settings.project-types.index')
            ->with('success', 'Project type created successfully.');
    }

    public function edit(ProjectType $projectType)
    {
        return view('admin.settings.project-types.edit', compact('projectType'));
    }

    public function update(Request $request, ProjectType $projectType)
    {
        $request->validate([
            'name'    => 'required|string|max:100|unique:project_types,name,' . $projectType->id,
            'name_en' => 'nullable|string|max:100',
        ]);

        $projectType->update($request->only('name', 'name_en'));

        return redirect()->route('admin.settings.project-types.index')
            ->with('success', 'Project type updated successfully.');
    }

    public function destroy(ProjectType $projectType)
    {
        $projectType->delete();

        return redirect()->route('admin.settings.project-types.index')
            ->with('success', 'Project type deleted successfully.');
    }
}
