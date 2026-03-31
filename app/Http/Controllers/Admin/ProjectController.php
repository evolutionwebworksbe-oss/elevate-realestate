<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\ProjectImage;
use App\Models\ProjectVideo;
use App\Models\ProjectDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('projectType')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $projectTypes = ProjectType::orderBy('name')->get();
        return view('admin.projects.create', compact('projectTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_nl'         => 'required|string|max:255',
            'title_en'         => 'nullable|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:projects,slug',
            'excerpt_nl'       => 'nullable|string|max:500',
            'excerpt_en'       => 'nullable|string|max:500',
            'description_nl'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'project_type_id'  => 'nullable|exists:project_types,id',
            'status'           => 'required|in:ongoing,completed,coming_soon,planning',
            'location'         => 'nullable|string|max:255',
            'total_units'      => 'nullable|integer|min:0',
            'total_area'       => 'nullable|numeric|min:0',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'is_featured'      => 'boolean',
            'is_published'     => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image'   => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = $validated['slug']
            ? \Illuminate\Support\Str::slug($validated['slug'])
            : Project::generateSlug($validated['title_nl']);

        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->storeImage($request->file('featured_image'));
        }

        $project = Project::create($validated);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', 'Project created. Add images, videos and downloads below.');
    }

    public function edit(Project $project)
    {
        $project->load('images', 'videos', 'downloads', 'projectType');
        $projectTypes = ProjectType::orderBy('name')->get();
        return view('admin.projects.edit', compact('project', 'projectTypes'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title_nl'         => 'required|string|max:255',
            'title_en'         => 'nullable|string|max:255',
            'slug'             => 'required|string|max:255|unique:projects,slug,' . $project->id,
            'excerpt_nl'       => 'nullable|string|max:500',
            'excerpt_en'       => 'nullable|string|max:500',
            'description_nl'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'project_type_id'  => 'nullable|exists:project_types,id',
            'status'           => 'required|in:ongoing,completed,coming_soon,planning',
            'location'         => 'nullable|string|max:255',
            'total_units'      => 'nullable|integer|min:0',
            'total_area'       => 'nullable|numeric|min:0',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'is_featured'      => 'boolean',
            'is_published'     => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image'   => 'nullable|image|max:5120',
        ]);

        $validated['slug']         = \Illuminate\Support\Str::slug($validated['slug']);
        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('featured_image')) {
            // Delete old featured image
            if ($project->featured_image) {
                @unlink(public_path('portal/projects/' . $project->featured_image));
            }
            $validated['featured_image'] = $this->storeImage($request->file('featured_image'));
        }

        $project->update($validated);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Delete featured image
        if ($project->featured_image) {
            @unlink(public_path('portal/projects/' . $project->featured_image));
        }

        // Delete gallery images
        foreach ($project->images as $image) {
            @unlink(public_path('portal/projects/gallery/' . $image->path));
        }

        // Delete download files
        foreach ($project->downloads as $download) {
            if ($download->file_path) {
                @unlink(public_path('portal/projects/downloads/' . $download->file_path));
            }
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    // ─── Gallery ────────────────────────────────────────────────────────────────

    public function uploadGallery(Request $request, Project $project)
    {
        $request->validate(['images.*' => 'required|image|max:5120']);

        $lastOrder = $project->images()->max('display_order') ?? -1;

        foreach ($request->file('images') as $file) {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('portal/projects/gallery'), $filename);

            ProjectImage::create([
                'project_id'    => $project->id,
                'path'          => $filename,
                'display_order' => ++$lastOrder,
            ]);
        }

        return back()->with('success', 'Images uploaded.');
    }

    public function deleteImage(Project $project, ProjectImage $image)
    {
        @unlink(public_path('portal/projects/gallery/' . $image->path));
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }

    public function reorderImages(Request $request, Project $project)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->order as $position => $imageId) {
            ProjectImage::where('id', $imageId)
                ->where('project_id', $project->id)
                ->update(['display_order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    // ─── Videos ─────────────────────────────────────────────────────────────────

    public function storeVideo(Request $request, Project $project)
    {
        $request->validate([
            'url'   => 'required|url|max:500',
            'title' => 'nullable|string|max:255',
        ]);

        $lastOrder = $project->videos()->max('display_order') ?? -1;

        ProjectVideo::create([
            'project_id'    => $project->id,
            'url'           => $request->url,
            'title'         => $request->title,
            'display_order' => $lastOrder + 1,
        ]);

        return back()->with('success', 'Video added.');
    }

    public function destroyVideo(Project $project, ProjectVideo $video)
    {
        $video->delete();
        return back()->with('success', 'Video removed.');
    }

    // ─── Downloads ──────────────────────────────────────────────────────────────

    public function storeDownload(Request $request, Project $project)
    {
        $request->validate([
            'label'        => 'required|string|max:255',
            'file'         => 'nullable|file|max:20480',
            'external_url' => 'nullable|url|max:500',
        ]);

        $lastOrder = $project->downloads()->max('display_order') ?? -1;

        $filePath = null;
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $filePath = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('portal/projects/downloads'), $filePath);
        }

        ProjectDownload::create([
            'project_id'    => $project->id,
            'label'         => $request->label,
            'file_path'     => $filePath,
            'external_url'  => $request->external_url,
            'display_order' => $lastOrder + 1,
        ]);

        return back()->with('success', 'Download added.');
    }

    public function destroyDownload(Project $project, ProjectDownload $download)
    {
        if ($download->file_path) {
            @unlink(public_path('portal/projects/downloads/' . $download->file_path));
        }
        $download->delete();

        return back()->with('success', 'Download removed.');
    }

    // ─── Helpers ────────────────────────────────────────────────────────────────

    private function storeImage($file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('portal/projects'), $filename);
        return $filename;
    }
}
