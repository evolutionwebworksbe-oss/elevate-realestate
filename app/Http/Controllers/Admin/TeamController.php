<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\TeamTitleType;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::with('titleTypes')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(20);
        return view('admin.team.index', compact('teamMembers'));
    }

    public function create()
    {
        $titleTypes = TeamTitleType::orderBy('name')->get();
        return view('admin.team.create', compact('titleTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'titles' => 'required|array|min:1',
            'titles.*' => 'exists:team_title_type,id',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'show_as_agent' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);
    
        // Handle photo upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $sanitizedName = preg_replace('/\s+/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName;
            
            $uploadPath = public_path('portal/img/team');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['image'] = 'img/team/' . $filename;
        }

        // Remove titles from validated array for create
        $titles = $validated['titles'];
        unset($validated['titles']);
        
        // Set display_order, default to 0
        $validated['display_order'] = $request->input('display_order', 0);
        
        // Set show_as_agent, default to false
        $validated['show_as_agent'] = $request->has('show_as_agent') ? true : false;
        
        // Keep the title field for backward compatibility (use first title)
        $validated['title'] = $titles[0];
    
        $teamMember = TeamMember::create($validated);
        
        // Attach all selected titles
        $teamMember->titleTypes()->attach($titles);
    
        return redirect()->route('admin.team.index')
            ->with('success', 'Team member successfully added');
    }
    
    public function update(Request $request, TeamMember $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'titles' => 'required|array|min:1',
            'titles.*' => 'exists:team_title_type,id',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'show_as_agent' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);
    
        // Handle photo upload
        if ($request->hasFile('image')) {
            // Delete old photo
            if ($team->image && file_exists(public_path('portal/' . $team->image))) {
                unlink(public_path('portal/' . $team->image));
            }
            
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $sanitizedName = preg_replace('/\s+/', '_', $originalName);
            $filename = time() . '_' . $sanitizedName;
            
            $uploadPath = public_path('portal/img/team');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['image'] = 'img/team/' . $filename;
        }

        // Remove titles from validated array
        $titles = $validated['titles'];
        unset($validated['titles']);
        
        // Set display_order
        $validated['display_order'] = $request->input('display_order', $team->display_order ?? 0);
        
        // Set show_as_agent
        $validated['show_as_agent'] = $request->has('show_as_agent') ? true : false;
        
        // Keep the title field for backward compatibility (use first title)
        $validated['title'] = $titles[0];
    
        $team->update($validated);
        
        // Sync titles
        $team->titleTypes()->sync($titles);
    
        return redirect()->route('admin.team.show', $team)
            ->with('success', 'Team member successfully updated');
    }
    
    public function destroy(TeamMember $team)
    {
        // Delete photo if exists
        if ($team->image && file_exists(public_path('portal/' . $team->image))) {
            unlink(public_path('portal/' . $team->image));
        }
        
        $team->delete();
    
        return redirect()->route('admin.team.index')
            ->with('success', 'Team member successfully deleted');
    }

    public function show(TeamMember $team)
    {
        $team->load('titleTypes', 'properties');
        return view('admin.team.show', compact('team'));
    }

    public function edit(TeamMember $team)
    {
        $titleTypes = TeamTitleType::orderBy('name')->get();
        $team->load('titleTypes');
        return view('admin.team.edit', compact('team', 'titleTypes'));
    }
}
