<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => Str::of($title)->trim('.')->toString(),
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'summary' => fake()->sentence(18),
            'challenge' => fake()->paragraphs(2, true),
            'solution' => fake()->paragraphs(3, true),
            'results' => fake()->paragraphs(2, true),
            'repository_url' => 'https://github.com/tu-usuario/'.Str::slug($title),
            'demo_url' => fake()->boolean(60)
                ? 'https://example.com/'.Str::slug($title)
                : null,
            'cover_image' => null,
            'is_featured' => false,
            'is_published' => false,
            'position' => fake()->numberBetween(1, 100),
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => [
            'is_featured' => true,
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}
