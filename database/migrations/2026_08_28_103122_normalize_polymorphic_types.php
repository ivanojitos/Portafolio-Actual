<?php

use App\Models\Profile;
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('taggables')
            ->where('taggable_type', Project::class)
            ->update([
                'taggable_type' => 'project',
            ]);
    }

    public function down(): void
    {
        DB::table('taggables')
            ->where('taggable_type', 'project')
            ->update([
                'taggable_type' => Project::class,
            ]);

        DB::table('media')
            ->where('mediable_type', 'project')
            ->update([
                'mediable_type' => Project::class,
            ]);

        DB::table('media')
            ->where('mediable_type', 'profile')
            ->update([
                'mediable_type' => Profile::class,
            ]);
    }
};
