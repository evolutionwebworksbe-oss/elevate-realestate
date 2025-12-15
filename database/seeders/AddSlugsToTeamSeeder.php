<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;
use Illuminate\Support\Str;

class AddSlugsToTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMembers = TeamMember::whereNull('slug')->orWhere('slug', '')->get();
        
        foreach ($teamMembers as $member) {
            $baseSlug = Str::slug($member->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Ensure slug is unique
            while (TeamMember::where('slug', $slug)->where('id', '!=', $member->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $member->slug = $slug;
            $member->save();
            
            $this->command->info("Added slug '{$slug}' for {$member->name}");
        }
        
        $this->command->info('Slugs added to all team members!');
    }
}
