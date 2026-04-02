<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\ProjectImage;
use App\Models\ProjectVideo;
use App\Models\ProjectDownload;
use App\Services\ImageService;
use Illuminate\Http\Request;

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

    public function store(Request $request, ImageService $imageService)
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
            $validated['featured_image'] = $this->storeImage($request->file('featured_image'), 'portal/projects', $imageService);
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

    public function update(Request $request, Project $project, ImageService $imageService)
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

        $oldFeaturedPath = null;
        if ($request->hasFile('featured_image')) {
            $oldFeaturedPath = $project->featured_image ? public_path('portal/projects/' . $project->featured_image) : null;
            $validated['featured_image'] = $this->storeImage($request->file('featured_image'), 'portal/projects', $imageService);
        }

        try {
            $project->update($validated);

            if ($oldFeaturedPath) {
                $imageService->deleteImageWithVariants($oldFeaturedPath);
            }
        } catch (\Throwable $e) {
            if (!empty($validated['featured_image'])) {
                $imageService->deleteImageWithVariants(public_path('portal/projects/' . $validated['featured_image']));
            }
            throw $e;
        }

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $imageService = app(ImageService::class);

        // Delete featured image
        if ($project->featured_image) {
            $imageService->deleteImageWithVariants(public_path('portal/projects/' . $project->featured_image));
        }

        // Delete gallery images
        foreach ($project->images as $image) {
            $imageService->deleteImageWithVariants(public_path('portal/projects/gallery/' . $image->path));
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

    public function uploadGallery(Request $request, Project $project, ImageService $imageService)
    {
        $request->validate([
            'images' => 'nullable|array',
            'images.*' => 'required|image|max:5120',
            'image' => 'nullable|image|max:5120',
        ]);

        $files = [];

        if ($request->hasFile('image')) {
            $files[] = $request->file('image');
        }

        if ($request->hasFile('images')) {
            $files = array_merge($files, $request->file('images'));
        }

        if (count($files) === 0) {
            $message = 'No images selected.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return back()->withErrors(['images' => $message]);
        }

        $lastOrder = $project->images()->max('display_order') ?? -1;
        $uploadedImages = [];

        foreach ($files as $file) {
            $filename = $this->storeImage($file, 'portal/projects/gallery', $imageService);

            $image = ProjectImage::create([
                'project_id'    => $project->id,
                'path'          => $filename,
                'display_order' => ++$lastOrder,
            ]);

            $uploadedImages[] = [
                'id' => $image->id,
                'url' => asset('portal/projects/gallery/' . $image->path),
                'path' => $image->path,
            ];
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'images' => $uploadedImages,
                'message' => count($uploadedImages) . ' image(s) uploaded.',
            ]);
        }

        return back()->with('success', 'Images uploaded.');
    }

    public function deleteImage(Project $project, ProjectImage $image)
    {
        app(ImageService::class)->deleteImageWithVariants(public_path('portal/projects/gallery/' . $image->path));
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

    private function storeImage($file, string $relativeDirectory, ImageService $imageService): string
    {
        $stored = $imageService->storeOptimizedUpload($file, $relativeDirectory);

        return $stored['filename'];
    }
}
