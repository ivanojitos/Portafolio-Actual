<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-6 years', '-2 years');
        $endedAt = fake()->dateTimeBetween($startedAt, '-1 year');

        return [
            'company' => fake()->company(),
            'job_title' => fake()->randomElement([
                'Desarrollador de software',
                'Desarrollador backend',
                'Desarrollador full stack',
                'Ingeniero de software',
            ]),
            'employment_type' => fake()->randomElement([
                'Tiempo completo',
                'Freelance',
                'Contrato',
            ]),
            'location' => fake()->city().', México',
            'company_url' => fake()->optional(0.7)->url(),
            'summary' => fake()->paragraph(3),
            'achievements' => [
                fake()->sentence(12),
                fake()->sentence(12),
                fake()->sentence(12),
            ],
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'is_current' => false,
            'is_published' => false,
            'position' => fake()->numberBetween(1, 100),
        ];
    }

    public function current(): static
    {
        return $this->state(fn (): array => [
            'ended_at' => null,
            'is_current' => true,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'is_published' => true,
        ]);
    }
}
