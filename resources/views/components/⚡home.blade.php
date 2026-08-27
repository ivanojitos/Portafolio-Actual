<?php

use App\Models\Project;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function projects()
    {
        return Project::query()
            ->published()
            ->featured()
            ->ordered()
            ->limit(6)
            ->get();
    }
};

?>

<div>
    <header class="border-b border-white/10">
        <nav
            class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8"
            aria-label="Navegación principal"
        >
            <a
                href="#inicio"
                class="text-lg font-bold tracking-tight text-white"
            >
                Mi Portafolio
            </a>

            <div class="hidden items-center gap-8 text-sm text-slate-300 md:flex">
                <a
                    href="#inicio"
                    class="transition hover:text-cyan-300"
                >
                    Inicio
                </a>

                <a
                    href="#proyectos"
                    class="transition hover:text-cyan-300"
                >
                    Proyectos
                </a>

                <a
                    href="#contacto"
                    class="transition hover:text-cyan-300"
                >
                    Contacto
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section
            id="inicio"
            class="relative isolate overflow-hidden"
        >
            <div
                class="absolute left-1/2 top-10 -z-10 h-80 w-80 -translate-x-1/2 rounded-full bg-cyan-400/20 blur-3xl"
                aria-hidden="true"
            ></div>

            <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8 lg:py-32">
                <div class="max-w-4xl">
                    <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                        Desarrollador de software
                    </p>

                    <h1 class="mt-6 text-4xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">
                        Construyo software seguro,
                        <span class="text-cyan-300">mantenible</span>
                        y preparado para crecer.
                    </h1>

                    <p class="mt-8 max-w-2xl text-lg leading-8 text-slate-300">
                        Desarrollo aplicaciones web con Laravel, PHP y
                        tecnologías modernas. Transformo problemas complejos
                        en productos claros, rápidos y confiables.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <a
                            href="#proyectos"
                            class="rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200 focus:outline-none focus:ring-2 focus:ring-cyan-300"
                        >
                            Ver proyectos
                        </a>

                        <a
                            href="#contacto"
                            class="rounded-xl border border-white/20 px-6 py-3 font-semibold text-white transition hover:border-cyan-300 hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300"
                        >
                            Contactarme
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section
            id="proyectos"
            class="border-y border-white/10 bg-slate-900/40"
        >
            <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
                <div class="max-w-2xl">
                    <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                        Trabajo destacado
                    </p>

                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Proyectos y casos de estudio
                    </h2>

                    <p class="mt-5 text-lg leading-8 text-slate-300">
                        Soluciones construidas aplicando seguridad,
                        arquitectura mantenible y pruebas automatizadas.
                    </p>
                </div>

                @if ($this->projects->isEmpty())
                    <div class="mt-12 rounded-2xl border border-dashed border-white/20 p-10 text-center">
                        <p class="text-slate-300">
                            Próximamente publicaré nuevos proyectos.
                        </p>
                    </div>
                @else
                    <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->projects as $project)
                            <article
                                wire:key="project-{{ $project->id }}"
                                class="group flex flex-col rounded-2xl border border-white/10 bg-slate-950/70 p-6 shadow-xl shadow-black/10 transition duration-300 hover:-translate-y-1 hover:border-cyan-300/50"
                            >
                                <div class="flex items-center justify-between gap-4">
                                    <span class="rounded-full bg-cyan-300/10 px-3 py-1 text-xs font-semibold text-cyan-300">
                                        Destacado
                                    </span>

                                    <span class="font-mono text-xs text-slate-500">
                                        {{ str_pad((string) $project->position, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>

                                <h3 class="mt-6 text-xl font-bold text-white">
                                    {{ $project->title }}
                                </h3>

                                <p class="mt-4 flex-1 leading-7 text-slate-400">
                                    {{ $project->summary }}
                                </p>

                                <div class="mt-8 flex flex-wrap gap-5 text-sm font-semibold">
                                    @if ($project->repository_url)
                                        <a
                                            href="{{ $project->repository_url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-cyan-300 transition hover:text-cyan-200"
                                        >
                                            Ver código →
                                        </a>
                                    @endif

                                    @if ($project->demo_url)
                                        <a
                                            href="{{ $project->demo_url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-slate-300 transition hover:text-white"
                                        >
                                            Demostración ↗
                                        </a>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section id="contacto">
            <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
                <div class="rounded-3xl border border-cyan-300/20 bg-cyan-300/5 px-6 py-12 text-center sm:px-12">
                    <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                        Trabajemos juntos
                    </p>

                    <h2 class="mt-4 text-3xl font-bold text-white">
                        ¿Tienes un proyecto en mente?
                    </h2>

                    <p class="mx-auto mt-5 max-w-2xl text-slate-300">
                        Estoy disponible para colaborar en aplicaciones web,
                        APIs y sistemas empresariales.
                    </p>

                    <a
                        href="ivanalvarez2507@gmail.com"
                        class="mt-8 inline-flex rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200"
                    >
                        Enviar correo
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-6 py-8 text-sm text-slate-500 lg:px-8">
            &copy; {{ now()->year }} Mi Portafolio.
            Desarrollado con Laravel y Livewire.
        </div>
    </footer>
</div>
