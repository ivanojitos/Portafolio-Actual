<?php

use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::app')]
#[Title('Panel administrativo')]
class extends Component
{
    #[Computed]
    public function stats(): array
    {
        return [
            'published_projects' => Project::query()
                ->where('is_published', true)
                ->count(),

            'draft_projects' => Project::query()
                ->where('is_published', false)
                ->count(),

            'experiences' => Experience::query()
                ->published()
                ->count(),

            'skills' => Skill::query()
                ->published()
                ->count(),

            'pending_messages' => ContactMessage::query()
                ->pending()
                ->count(),
        ];
    }
};

?>

<div class="min-h-screen">
    <livewire:admin-navigation />

    <main class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div>
            <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                Área privada
            </p>

            <h1 class="mt-4 text-4xl font-bold text-white">
                Panel administrativo
            </h1>

            <p class="mt-4 text-slate-300">
                Bienvenido, {{ auth()->user()->name }}.
            </p>
        </div>

        <section
            class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3"
            aria-label="Resumen del portafolio"
        >
            <article class="rounded-2xl border border-white/10 bg-slate-900/60 p-6">
                <p class="text-sm text-slate-400">
                    Proyectos publicados
                </p>

                <strong class="mt-3 block text-4xl font-black text-cyan-300">
                    {{ $this->stats['published_projects'] }}
                </strong>
            </article>

            <article class="rounded-2xl border border-white/10 bg-slate-900/60 p-6">
                <p class="text-sm text-slate-400">
                    Proyectos en borrador
                </p>

                <strong class="mt-3 block text-4xl font-black text-slate-200">
                    {{ $this->stats['draft_projects'] }}
                </strong>
            </article>

            <article class="rounded-2xl border border-white/10 bg-slate-900/60 p-6">
                <p class="text-sm text-slate-400">
                    Experiencias publicadas
                </p>

                <strong class="mt-3 block text-4xl font-black text-cyan-300">
                    {{ $this->stats['experiences'] }}
                </strong>
            </article>

            <article class="rounded-2xl border border-white/10 bg-slate-900/60 p-6">
                <p class="text-sm text-slate-400">
                    Habilidades publicadas
                </p>

                <strong class="mt-3 block text-4xl font-black text-cyan-300">
                    {{ $this->stats['skills'] }}
                </strong>
            </article>

            <article class="rounded-2xl border border-amber-300/20 bg-amber-300/5 p-6">
                <p class="text-sm text-amber-100/70">
                    Mensajes pendientes
                </p>

                <strong class="mt-3 block text-4xl font-black text-amber-300">
                    {{ $this->stats['pending_messages'] }}
                </strong>
            </article>
        </section>

        <section class="mt-12 rounded-2xl border border-white/10 bg-slate-900/40 p-8">
            <h2 class="text-xl font-bold text-white">
                Próximas herramientas
            </h2>

            <ul class="mt-5 grid gap-3 text-sm text-slate-400 sm:grid-cols-2">
                <li>Administración de proyectos</li>
                <li>Administración de experiencia</li>
                <li>Administración de habilidades</li>
                <li>Lectura de mensajes</li>
                <li>Actualización del perfil</li>
                <li>Carga segura de imágenes y CV</li>
            </ul>
        </section>
    </main>
</div>
