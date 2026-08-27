<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::factory()
            ->count(3)
            ->featured()
            ->create();

        Project::factory()
            ->count(5)
            ->published()
            ->create();

        Project::factory()
            ->count(2)
            ->create();
    }
}
