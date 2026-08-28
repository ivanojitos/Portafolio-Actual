<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::query()->firstOrNew();

        $profile->fill([
            'full_name' => 'Ivan Alvarez Valencia',
            'headline' => 'Ingeniero de Software especializado en soluciones escalables',
            'location' => 'Puebla, México',

            'introduction' => 'Ingeniero de Software con más de siete años de experiencia diseñando, desarrollando y escalando aplicaciones web, sistemas distribuidos y plataformas de alto tráfico.',

            'about' => 'Especializado en el desarrollo de soluciones robustas y escalables con Laravel, Vue.js, Node.js y C#. Cuento con experiencia en arquitectura de software, optimización del rendimiento, modernización de sistemas legacy, integración de servicios y entrega continua. También estoy ampliando mi stack tecnológico con Python.',

            'public_email' => 'ivanalvarez2507@gmail.com',
            'github_url' => 'https://github.com/ivanojitos?tab=repositories',
            'linkedin_url' => 'https://www.linkedin.com/in/ivan-alvarez-valencia-042894127/',

            'avatar_path' => null,
            'resume_path' => null,

            'is_available' => false,
            'is_published' => true,

            'meta_title' => 'Ivan Alvarez Valencia | Ingeniero de Software',
            'meta_description' => 'Portafolio de Ivan Alvarez Valencia, ingeniero de software especializado en Laravel, arquitectura y aplicaciones escalables.',
        ]);

        $profile->save();
    }
}
