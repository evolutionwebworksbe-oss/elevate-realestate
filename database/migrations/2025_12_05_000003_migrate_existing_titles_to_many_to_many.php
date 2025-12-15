<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get all team members that have a title set
        $teamMembers = DB::table('team')
            ->whereNotNull('title')
            ->where('title', '>', 0)
            ->get();

        foreach ($teamMembers as $member) {
            // Check if this title exists in team_title_type table
            $titleExists = DB::table('team_title_type')
                ->where('id', $member->title)
                ->exists();

            if ($titleExists) {
                // Insert into the pivot table if not already exists
                DB::table('team_member_titles')->insertOrIgnore([
                    'team_member_id' => $member->id,
                    'team_title_type_id' => $member->title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Clear the pivot table (optional - only if you want rollback to work)
        DB::table('team_member_titles')->truncate();
    }
};
