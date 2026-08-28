<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::query()->update([
            'is_featured' => false,
            'is_published' => false,
            'published_at' => null,
        ]);

        $laravel = Tag::query()->firstOrCreate(
            ['slug' => 'laravel'],
            [
                'name' => 'Laravel',
                'color' => 'red',
            ]
        );

        $projects = [
            [
                'title' => 'Sistema de viáticos',
                'slug' => 'sistema-de-viaticos',
                'summary' => 'Plataforma empresarial para digitalizar solicitudes de viáticos, comprobantes y seguimiento de viajes.',

                'challenge' => 'El proceso dependía de documentos físicos, cargas manuales, tiempos de espera elevados y poca visibilidad sobre el seguimiento de los viajes. También era necesario procesar solicitudes relacionadas con SAP y administrar un volumen considerable de información.',

                'solution' => 'Desarrollé una plataforma completa con Laravel para centralizar la captura de solicitudes, la carga de comprobantes y el seguimiento de cada viaje. La solución organizó el flujo de información y redujo la dependencia de procesos basados en papel.',

                'results' => 'La plataforma permitió digitalizar el proceso de viáticos, centralizar la documentación y facilitar el seguimiento de solicitudes y viajes. Por confidencialidad, el código fuente y la aplicación interna no se encuentran disponibles públicamente.',

                'repository_url' => null,
                'demo_url' => null,
                'cover_image' => null,

                'is_featured' => true,
                'is_published' => true,
                'position' => 1,
                'published_at' => now(),
            ],
            [
                'title' => 'Sistema de solicitudes',
                'slug' => 'sistema-de-solicitudes',
                'summary' => 'Aplicación empresarial para centralizar solicitudes relacionadas con compras y licencias de SAP.',

                'challenge' => 'El proceso requería cargas individuales, generaba tiempos de espera y dificultaba la administración y seguimiento de las solicitudes. El volumen de información también representaba un reto para el procesamiento de datos.',

                'solution' => 'Desarrollé una aplicación completa con Laravel para concentrar las solicitudes en un único sistema, organizar su captura y facilitar la gestión de la información durante cada etapa del proceso.',

                'results' => 'La solución permitió simplificar la captura, centralizar la información y mejorar el seguimiento de las solicitudes. Por confidencialidad, el código fuente y la demostración interna no se publican.',

                'repository_url' => null,
                'demo_url' => null,
                'cover_image' => null,

                'is_featured' => true,
                'is_published' => true,
                'position' => 2,
                'published_at' => now(),
            ],
            [
                'title' => 'Plataforma de artículos',
                'slug' => 'plataforma-de-articulos',
                'summary' => 'Aplicación web construida para demostrar el desarrollo de una plataforma de contenidos utilizando Laravel.',

                'challenge' => 'El objetivo fue construir una aplicación web completa que permitiera demostrar conocimientos prácticos de Laravel, organización de una plataforma de contenidos y publicación de una solución funcional.',

                'solution' => 'Desarrollé la aplicación completa con Laravel, estructurando su lógica, vistas, rutas y persistencia de datos. El código fuente se publicó en GitHub y la aplicación se desplegó en Render para permitir su evaluación.',

                'results' => 'Se completó y publicó una versión funcional. El repositorio puede revisarse públicamente y la aplicación cuenta con una demostración accesible en línea.',

                'repository_url' => 'https://github.com/ivanojitos/plataforma_articulos',
                'demo_url' => 'https://plataforma-articulos.onrender.com/',
                'cover_image' => null,

                'is_featured' => true,
                'is_published' => true,
                'position' => 3,
                'published_at' => now(),
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::query()->updateOrCreate(
                ['slug' => $projectData['slug']],
                $projectData
            );

            $project->tags()->sync([
                $laravel->id,
            ]);
        }
    }
}
