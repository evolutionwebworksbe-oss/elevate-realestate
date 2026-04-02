<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Property;
use App\Services\ImageService;
use Illuminate\Console\Command;

class BackfillResponsiveImages extends Command
{
    protected $signature = 'images:backfill-responsive
                            {--property= : Only process one property ID}
                            {--project= : Only process one project ID}
                            {--only-missing : Skip images when at least one responsive variant already exists}';

    protected $description = 'Generate responsive image variants for existing property and project images';

    public function handle(ImageService $imageService): int
    {
        $propertyId = $this->option('property');
        $projectId = $this->option('project');
        $onlyMissing = (bool) $this->option('only-missing');

        if ($propertyId && $projectId) {
            $this->error('Use either --property or --project, not both.');
            return self::FAILURE;
        }

        $items = [];

        if ($propertyId) {
            $property = Property::with('images')->find($propertyId);
            if (!$property) {
                $this->error("Property {$propertyId} not found.");
                return self::FAILURE;
            }

            $items = array_merge($items, $this->propertyImages($property));
        } elseif ($projectId) {
            $project = Project::with('images')->find($projectId);
            if (!$project) {
                $this->error("Project {$projectId} not found.");
                return self::FAILURE;
            }

            $items = array_merge($items, $this->projectImages($project));
        } else {
            if (!$this->confirm('Generate responsive variants for all existing property and project images?')) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }

            Property::with('images')->chunk(100, function ($properties) use (&$items) {
                foreach ($properties as $property) {
                    $items = array_merge($items, $this->propertyImages($property));
                }
            });

            Project::with('images')->chunk(100, function ($projects) use (&$items) {
                foreach ($projects as $project) {
                    $items = array_merge($items, $this->projectImages($project));
                }
            });
        }

        $items = array_values(array_unique(array_filter($items)));

        if (empty($items)) {
            $this->warn('No images found to process.');
            return self::SUCCESS;
        }

        $this->info('Generating responsive image variants...');
        $bar = $this->output->createProgressBar(count($items));
        $bar->start();

        $processed = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($items as $fullPath) {
            if ($onlyMissing && $imageService->hasCompleteResponsiveSet($fullPath)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if ($imageService->backfillResponsiveVariants($fullPath)) {
                $processed++;
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Processed: {$processed}");

        if ($skipped > 0) {
            $this->info("Skipped: {$skipped}");
        }

        if ($failed > 0) {
            $this->warn("Failed: {$failed}");
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function propertyImages(Property $property): array
    {
        $paths = [];

        if ($property->featuredFoto) {
            $paths[] = public_path('portal' . $property->featuredFoto);
        }

        foreach ($property->images as $image) {
            if ($image->url) {
                $paths[] = public_path('portal' . $image->url);
            }
        }

        return $paths;
    }

    private function projectImages(Project $project): array
    {
        $paths = [];

        if ($project->featured_image) {
            $paths[] = public_path('portal/projects/' . $project->featured_image);
        }

        foreach ($project->images as $image) {
            if ($image->path) {
                $paths[] = public_path('portal/projects/gallery/' . $image->path);
            }
        }

        return $paths;
    }
}
