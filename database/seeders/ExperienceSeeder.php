<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        Experience::factory()
            ->current()
            ->published()
            ->create([
                'job_title' => 'Desarrollador de software',
                'position' => 1,
                'started_at' => now()->subYears(2),
            ]);

        Experience::factory()
            ->count(2)
            ->published()
            ->sequence(
                ['position' => 2],
                ['position' => 3],
            )
            ->create();
    }
}
