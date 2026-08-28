<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        Experience::query()->update([
            'is_published' => false,
        ]);

        $experiences = [
            [
                'company' => 'SERVIMSA',
                'job_title' => 'Gerente de TI',
                'employment_type' => 'Tiempo completo',
                'location' => 'México',
                'company_url' => null,
                'summary' => 'Liderazgo técnico y gestión integral de proyectos de software, desde el análisis inicial y diseño de la solución hasta su implementación, estabilización y entrega.',
                'achievements' => [
                    'Lideré proyectos aplicando metodologías ágiles, tradicionales, Design Thinking y Design Sprint.',
                    'Implementé un sistema para gestionar proyectos, versionamientos, peticiones HTTP e historial de movimientos mediante programación orientada a objetos y pipelines de Jenkins.',
                    'Realicé mantenimiento correctivo, solución de errores y refactorización para estabilizar sistemas existentes.',
                    'Integré servicios SOAP mediante WSDL para sistemas ERP, fiscales y solicitudes de proveedores.',
                    'Desarrollé sistemas escalables y aplicaciones de alta concurrencia.',
                    'Apliqué seguridad, Clean Architecture y prácticas DevOps con integración y entrega continua.',
                ],
                'started_at' => '2023-01-01',
                'ended_at' => null,
                'is_current' => true,
                'is_published' => true,
                'position' => 1,
            ],
            [
                'company' => 'SYCNOS',
                'job_title' => 'Desarrollador Full Stack',
                'employment_type' => 'Tiempo completo',
                'location' => 'Monterrey, México',
                'company_url' => null,
                'summary' => 'Desarrollo y modernización de aplicaciones internas y externas, participando en análisis, arquitectura, implementación, pruebas y despliegue.',
                'achievements' => [
                    'Apliqué principios SOLID, Clean Code, patrones de diseño y estándares de desarrollo.',
                    'Desarrollé aplicaciones desde cero, participando en todo el ciclo de vida del producto.',
                    'Adopté e implementé nuevas tecnologías en proyectos reales.',
                    'Integré APIs REST en aplicaciones desarrolladas con Flutter.',
                    'Modernicé aplicaciones legacy mediante actualizaciones de PHP, Laravel y dependencias.',
                    'Refactoricé código para adoptar nuevas versiones del framework y mejorar su mantenibilidad.',
                ],
                'started_at' => '2021-01-01',
                'ended_at' => '2023-01-01',
                'is_current' => false,
                'is_published' => true,
                'position' => 2,
            ],
            [
                'company' => 'SIATEC',
                'job_title' => 'Desarrollador C#',
                'employment_type' => 'Tiempo completo',
                'location' => 'México',
                'company_url' => null,
                'summary' => 'Desarrollo y mantenimiento de una aplicación de escritorio orientada al monitoreo y gestión de servicios de seguridad.',
                'achievements' => [
                    'Desarrollé una aplicación de escritorio para monitorear y gestionar servicios de seguridad.',
                    'Construí funcionalidades de monitoreo en tiempo real y generación de reportes.',
                    'Mejoré el rendimiento, la confiabilidad y la mantenibilidad del sistema.',
                    'Proporcioné soporte técnico, mantenimiento y mejoras continuas a la plataforma.',
                ],
                'started_at' => '2018-01-01',
                'ended_at' => '2021-01-01',
                'is_current' => false,
                'is_published' => true,
                'position' => 3,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::query()->updateOrCreate(
                [
                    'company' => $experience['company'],
                    'job_title' => $experience['job_title'],
                ],
                $experience
            );
        }
    }
}
