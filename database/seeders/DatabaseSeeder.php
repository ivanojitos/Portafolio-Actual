<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ProfileSeeder::class,
            TagSeeder::class,
            ProjectSeeder::class,
            ExperienceSeeder::class,
            SkillSeeder::class,
        ]);
    }
}
