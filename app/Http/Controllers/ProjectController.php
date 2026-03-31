<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectType;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('projectType')
            ->where('is_published', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('project_type_id', $request->type);
        }

        $projects     = $query->get();
        $projectTypes = ProjectType::has('projects')->orderBy('name')->get();

        SEOMeta::setTitle('Projecten - Elevate Real Estate');
        SEOMeta::setDescription('Bekijk onze vastgoedprojecten en portfolio.');

        return view('projects.index', compact('projects', 'projectTypes'));
    }

    public function show(string $slug)
    {
        $project = Project::with('images', 'videos', 'downloads', 'projectType')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        SEOMeta::setTitle(($project->meta_title ?: $project->getTranslatedTitle()) . ' - Elevate Real Estate');

        if ($project->meta_description) {
            SEOMeta::setDescription($project->meta_description);
        } elseif ($project->getTranslatedExcerpt()) {
            SEOMeta::setDescription($project->getTranslatedExcerpt());
        }

        OpenGraph::setTitle($project->meta_title ?: $project->getTranslatedTitle());

        if ($project->featured_image) {
            OpenGraph::addImage(asset('portal/projects/' . $project->featured_image));
        }

        return view('projects.show', compact('project'));
    }
}
