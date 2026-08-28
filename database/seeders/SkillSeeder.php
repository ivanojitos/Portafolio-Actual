<?php

namespace Database\Seeders;

use App\Enums\SkillCategory;
use App\Enums\SkillLevel;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Core,
                'summary' => 'Aplicaciones web, APIs, colas, caché, autenticación y pruebas.',
                'position' => 1,
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Core,
                'summary' => 'PHP moderno, orientación a objetos, enums y tipado.',
                'position' => 2,
            ],
            [
                'name' => 'APIs REST',
                'slug' => 'apis-rest',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Diseño, validación, autorización y documentación de APIs.',
                'position' => 3,
            ],
            [
                'name' => 'Livewire',
                'slug' => 'livewire',
                'category' => SkillCategory::Frontend,
                'level' => SkillLevel::Core,
                'summary' => 'Interfaces reactivas utilizando PHP y componentes.',
                'position' => 1,
            ],
            [
                'name' => 'Tailwind CSS',
                'slug' => 'tailwind-css',
                'category' => SkillCategory::Frontend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Diseño responsive, accesible y mantenible.',
                'position' => 2,
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'category' => SkillCategory::Frontend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Interactividad progresiva e integración con APIs.',
                'position' => 3,
            ],
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
                'category' => SkillCategory::Database,
                'level' => SkillLevel::Core,
                'summary' => 'Modelado relacional, índices y optimización de consultas.',
                'position' => 1,
            ],
            [
                'name' => 'PostgreSQL',
                'slug' => 'postgresql',
                'category' => SkillCategory::Database,
                'level' => SkillLevel::Proficient,
                'summary' => 'Bases de datos relacionales para aplicaciones modernas.',
                'position' => 2,
            ],
            [
                'name' => 'Redis',
                'slug' => 'redis',
                'category' => SkillCategory::Database,
                'level' => SkillLevel::Familiar,
                'summary' => 'Caché, sesiones, colas y rate limiting.',
                'position' => 3,
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'category' => SkillCategory::DevOps,
                'level' => SkillLevel::Proficient,
                'summary' => 'Entornos reproducibles para desarrollo y despliegue.',
                'position' => 1,
            ],
            [
                'name' => 'GitHub Actions',
                'slug' => 'github-actions',
                'category' => SkillCategory::DevOps,
                'level' => SkillLevel::Familiar,
                'summary' => 'Integración continua, pruebas y validación automática.',
                'position' => 2,
            ],
            [
                'name' => 'Git',
                'slug' => 'git',
                'category' => SkillCategory::Tools,
                'level' => SkillLevel::Core,
                'summary' => 'Control de versiones y flujos de trabajo colaborativos.',
                'position' => 1,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::query()->updateOrCreate(
                ['slug' => $skill['slug']],
                [
                    ...$skill,
                    'years_experience' => null,
                    'is_featured' => $skill['level'] === SkillLevel::Core,
                    'is_published' => true,
                ]
            );
        }
    }
}
