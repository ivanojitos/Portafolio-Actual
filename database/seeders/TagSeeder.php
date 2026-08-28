<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'color' => 'red',
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'color' => 'indigo',
            ],
            [
                'name' => 'Livewire',
                'slug' => 'livewire',
                'color' => 'pink',
            ],
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
                'color' => 'blue',
            ],
            [
                'name' => 'Tailwind CSS',
                'slug' => 'tailwind-css',
                'color' => 'cyan',
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'color' => 'yellow',
            ],
        ];

        foreach ($technologies as $technology) {
            Tag::query()->updateOrCreate(
                ['slug' => $technology['slug']],
                $technology
            );
        }


    }
}
