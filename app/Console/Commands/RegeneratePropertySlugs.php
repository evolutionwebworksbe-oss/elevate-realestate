<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Illuminate\Support\Str;

class RegeneratePropertySlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:regenerate-slugs {--dry-run : Preview changes without saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate property slugs to remove IDs and use only naam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be saved');
            $this->newLine();
        }
        
        $properties = Property::all();
        $this->info("Found {$properties->count()} properties to process");
        $this->newLine();
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($properties as $property) {
            $oldSlug = $property->slug;
            
            // Generate new slug based only on naam
            $newSlug = $this->generateUniqueSlug($property->naam, $property->id);
            
            // Check if slug needs updating
            if ($oldSlug === $newSlug) {
                $skipped++;
                continue;
            }
            
            $this->line("ID: {$property->id}");
            $this->line("  Naam: {$property->naam}");
            $this->line("  Old: {$oldSlug}");
            $this->line("  <fg=green>New: {$newSlug}</>");
            $this->newLine();
            
            if (!$dryRun) {
                $property->slug = $newSlug;
                $property->save();
            }
            
            $updated++;
        }
        
        $this->newLine();
        $this->info("✅ Summary:");
        $this->info("   Updated: {$updated}");
        $this->info("   Skipped: {$skipped}");
        $this->info("   Total: {$properties->count()}");
        
        if ($dryRun) {
            $this->newLine();
            $this->warn('🔥 Run without --dry-run to apply changes');
        } else {
            $this->newLine();
            $this->info('🎉 All slugs have been regenerated!');
        }
    }
    
    /**
     * Generate unique slug for property
     */
    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (Property::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
}
