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
        Skill::query()->update([
            'is_published' => false,
        ]);

        $skills = [
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Core,
                'summary' => 'Desarrollo, mantenimiento y modernización de aplicaciones Laravel.',
                'position' => 1,
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Core,
                'summary' => 'Desarrollo orientado a objetos, refactorización y actualización de aplicaciones legacy.',
                'position' => 2,
            ],
            [
                'name' => 'Node.js',
                'slug' => 'node-js',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Desarrollo de servicios y aplicaciones con JavaScript del lado del servidor.',
                'position' => 3,
            ],
            [
                'name' => 'C#',
                'slug' => 'c-sharp',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Core,
                'summary' => 'Aplicaciones de escritorio, monitoreo en tiempo real y sistemas empresariales.',
                'position' => 4,
            ],
            [
                'name' => 'ASP.NET',
                'slug' => 'asp-net',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Desarrollo de soluciones empresariales dentro del ecosistema .NET.',
                'position' => 5,
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Familiar,
                'summary' => 'Tecnología actualmente incorporada al stack profesional.',
                'position' => 6,
            ],
            [
                'name' => 'APIs REST',
                'slug' => 'apis-rest',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Diseño, consumo e integración de servicios HTTP.',
                'position' => 7,
            ],
            [
                'name' => 'SOAP y WSDL',
                'slug' => 'soap-wsdl',
                'category' => SkillCategory::Backend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Integración de servicios para sistemas ERP, fiscales y proveedores.',
                'position' => 8,
            ],
            [
                'name' => 'Vue.js',
                'slug' => 'vue-js',
                'category' => SkillCategory::Frontend,
                'level' => SkillLevel::Core,
                'summary' => 'Construcción de interfaces web modernas y aplicaciones de alto tráfico.',
                'position' => 1,
            ],
            [
                'name' => 'Angular',
                'slug' => 'angular',
                'category' => SkillCategory::Frontend,
                'level' => SkillLevel::Proficient,
                'summary' => 'Desarrollo de aplicaciones frontend estructuradas.',
                'position' => 2,
            ],
            [
                'name' => 'Flutter',
                'slug' => 'flutter',
                'category' => SkillCategory::Frontend,
                'level' => SkillLevel::Familiar,
                'summary' => 'Integración y consumo de APIs REST desde aplicaciones Flutter.',
                'position' => 3,
            ],
            [
                'name' => 'Clean Architecture',
                'slug' => 'clean-architecture',
                'category' => SkillCategory::Architecture,
                'level' => SkillLevel::Core,
                'summary' => 'Separación de responsabilidades y diseño de sistemas mantenibles.',
                'position' => 1,
            ],
            [
                'name' => 'SOLID y Clean Code',
                'slug' => 'solid-clean-code',
                'category' => SkillCategory::Architecture,
                'level' => SkillLevel::Core,
                'summary' => 'Principios y prácticas para construir software extensible y legible.',
                'position' => 2,
            ],
            [
                'name' => 'Patrones de diseño',
                'slug' => 'patrones-diseno',
                'category' => SkillCategory::Architecture,
                'level' => SkillLevel::Proficient,
                'summary' => 'Aplicación de patrones para resolver problemas recurrentes de diseño.',
                'position' => 3,
            ],
            [
                'name' => 'Jenkins',
                'slug' => 'jenkins',
                'category' => SkillCategory::DevOps,
                'level' => SkillLevel::Proficient,
                'summary' => 'Automatización de pipelines e integración continua.',
                'position' => 1,
            ],
            [
                'name' => 'CI/CD',
                'slug' => 'ci-cd',
                'category' => SkillCategory::DevOps,
                'level' => SkillLevel::Proficient,
                'summary' => 'Automatización de validaciones, integración y entrega de software.',
                'position' => 2,
            ],
            [
                'name' => 'Design Thinking',
                'slug' => 'design-thinking',
                'category' => SkillCategory::Tools,
                'level' => SkillLevel::Proficient,
                'summary' => 'Resolución de problemas de experiencia de usuario y diseño de servicios.',
                'position' => 1,
            ],
            [
                'name' => 'Design Sprint',
                'slug' => 'design-sprint',
                'category' => SkillCategory::Tools,
                'level' => SkillLevel::Proficient,
                'summary' => 'Validación rápida de ideas y soluciones de producto.',
                'position' => 2,
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
