<?php

use App\Models\Project;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Experience;
use App\Models\Skill;

new class extends Component {
    #[Computed]
    public function projects()
    {
        return Project::query()
            ->with(['tags:id,name,slug,color'])
            ->published()
            ->featured()
            ->ordered()
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function experiences()
    {
        return Experience::query()->published()->ordered()->get();
    }
    #[Computed]
    public function skillsByCategory()
    {
        return Skill::query()->published()->ordered()->get()->groupBy(fn(Skill $skill): string => $skill->category->value);
    }
};

?>

<div>
    <header class="border-b border-white/10">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8"
            aria-label="Navegación principal">
            <a href="#inicio" class="text-lg font-bold tracking-tight text-white">
                Mi Portafolio
            </a>

            <div class="hidden items-center gap-8 text-sm text-slate-300 md:flex">
                <a href="#inicio" class="transition hover:text-cyan-300">
                    Inicio
                </a>

                <a href="#habilidades" class="transition hover:text-cyan-300">
                    Habilidades
                </a>

                <a href="#experiencia" class="transition hover:text-cyan-300">
                    Experiencia
                </a>

                <a href="#proyectos" class="transition hover:text-cyan-300">
                    Proyectos
                </a>

                <a href="#contacto" class="transition hover:text-cyan-300">
                    Contacto
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section id="inicio" class="relative isolate overflow-hidden">
            <div class="absolute left-1/2 top-10 -z-10 h-80 w-80 -translate-x-1/2 rounded-full bg-cyan-400/20 blur-3xl"
                aria-hidden="true"></div>

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
                        <a href="#proyectos"
                            class="rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200 focus:outline-none focus:ring-2 focus:ring-cyan-300">
                            Ver proyectos
                        </a>

                        <a href="#contacto"
                            class="rounded-xl border border-white/20 px-6 py-3 font-semibold text-white transition hover:border-cyan-300 hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300">
                            Contactarme
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="habilidades" class="border-t border-white/10 bg-slate-900/30">
            <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
                <div class="max-w-2xl">
                    <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                        Stack tecnológico
                    </p>

                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Habilidades técnicas
                    </h2>

                    <p class="mt-5 text-lg leading-8 text-slate-300">
                        Tecnologías utilizadas para construir aplicaciones seguras,
                        mantenibles y preparadas para producción.
                    </p>
                </div>

                @if ($this->skillsByCategory->isEmpty())
                    <div class="mt-12 rounded-2xl border border-dashed border-white/20 p-10 text-center">
                        <p class="text-slate-300">
                            Las habilidades se publicarán próximamente.
                        </p>
                    </div>
                @else
                    <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($this->skillsByCategory as $categorySkills)
                            @php
                                $category = $categorySkills->first()->category;
                            @endphp

                            <section class="rounded-2xl border border-white/10 bg-slate-950/70 p-6">
                                <h3 class="text-lg font-bold text-white">
                                    {{ $category->label() }}
                                </h3>

                                <div class="mt-6 space-y-5">
                                    @foreach ($categorySkills as $skill)
                                        <article wire:key="skill-{{ $skill->id }}"
                                            class="border-t border-white/10 pt-5 first:border-0 first:pt-0">
                                            <div class="flex items-start justify-between gap-4">
                                                <h4 class="font-semibold text-cyan-200">
                                                    {{ $skill->name }}
                                                </h4>

                                                <span
                                                    class="rounded-full bg-white/5 px-2.5 py-1 text-[0.7rem] text-slate-400">
                                                    {{ $skill->level->label() }}
                                                </span>
                                            </div>

                                            @if ($skill->summary)
                                                <p class="mt-2 text-sm leading-6 text-slate-400">
                                                    {{ $skill->summary }}
                                                </p>
                                            @endif

                                            @if ($skill->years_experience)
                                                <p class="mt-2 text-xs text-slate-500">
                                                    {{ $skill->years_experience }}
                                                    {{ $skill->years_experience === 1 ? 'año' : 'años' }}
                                                    de experiencia
                                                </p>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>


        <section id="experiencia" class="border-t border-white/10">
            <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
                <div class="max-w-2xl">
                    <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                        Trayectoria profesional
                    </p>

                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Experiencia
                    </h2>

                    <p class="mt-5 text-lg leading-8 text-slate-300">
                        Experiencia desarrollando soluciones, mejorando sistemas
                        y resolviendo problemas de negocio mediante software.
                    </p>
                </div>

                @if ($this->experiences->isEmpty())
                    <div class="mt-12 rounded-2xl border border-dashed border-white/20 p-10 text-center">
                        <p class="text-slate-300">
                            La experiencia profesional se publicará próximamente.
                        </p>
                    </div>
                @else
                    <div class="relative mt-14 space-y-8">
                        <div class="absolute bottom-0 left-3 top-0 w-px bg-white/10 md:left-48" aria-hidden="true">
                        </div>

                        @foreach ($this->experiences as $experience)
                            <article wire:key="experience-{{ $experience->id }}"
                                class="relative grid gap-5 pl-10 md:grid-cols-[10rem_1fr] md:gap-12 md:pl-0">
                                <div class="absolute left-[7px] top-2 h-3 w-3 rounded-full border-2 border-cyan-300 bg-slate-950 md:left-[185px]"
                                    aria-hidden="true"></div>

                                <div class="text-sm text-slate-400">
                                    <time datetime="{{ $experience->started_at->format('Y-m') }}">
                                        {{ ucfirst($experience->started_at->translatedFormat('M Y')) }}
                                    </time>

                                    <span aria-hidden="true"> — </span>

                                    @if ($experience->is_current)
                                        <span class="font-semibold text-cyan-300">
                                            Actualidad
                                        </span>
                                    @elseif ($experience->ended_at)
                                        <time datetime="{{ $experience->ended_at->format('Y-m') }}">
                                            {{ ucfirst($experience->ended_at->translatedFormat('M Y')) }}
                                        </time>
                                    @endif
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-6">
                                    <h3 class="text-xl font-bold text-white">
                                        {{ $experience->job_title }}
                                    </h3>

                                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                                        @if ($experience->company_url)
                                            <a href="{{ $experience->company_url }}" target="_blank"
                                                rel="noopener noreferrer"
                                                class="font-semibold text-cyan-300 transition hover:text-cyan-200">
                                                {{ $experience->company }}
                                            </a>
                                        @else
                                            <span class="font-semibold text-cyan-300">
                                                {{ $experience->company }}
                                            </span>
                                        @endif

                                        @if ($experience->location)
                                            <span class="text-slate-500">
                                                {{ $experience->location }}
                                            </span>
                                        @endif

                                        @if ($experience->employment_type)
                                            <span class="text-slate-500">
                                                {{ $experience->employment_type }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mt-5 leading-7 text-slate-300">
                                        {{ $experience->summary }}
                                    </p>

                                    @if (!empty($experience->achievements))
                                        <ul class="mt-5 space-y-3 text-sm leading-6 text-slate-400">
                                            @foreach ($experience->achievements as $achievement)
                                                <li class="flex gap-3">
                                                    <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-cyan-300"
                                                        aria-hidden="true"></span>

                                                    <span>{{ $achievement }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section id="proyectos" class="border-y border-white/10 bg-slate-900/40">
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
                            <article wire:key="project-{{ $project->id }}"
                                class="group flex flex-col rounded-2xl border border-white/10 bg-slate-950/70 p-6 shadow-xl shadow-black/10 transition duration-300 hover:-translate-y-1 hover:border-cyan-300/50">
                                <div class="flex items-center justify-between gap-4">
                                    <span
                                        class="rounded-full bg-cyan-300/10 px-3 py-1 text-xs font-semibold text-cyan-300">
                                        Destacado
                                    </span>

                                    <span class="font-mono text-xs text-slate-500">
                                        {{ str_pad((string) $project->position, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>

                                <h3 class="mt-6 text-xl font-bold text-white">
                                    <a href="{{ route('projects.show', $project) }}" wire:navigate
                                        class="transition hover:text-cyan-300">
                                        {{ $project->title }}
                                    </a>
                                </h3>

                                <p class="mt-4 leading-7 text-slate-400">
                                    {{ $project->summary }}
                                </p>

                                @if ($project->tags->isNotEmpty())
                                    <ul class="mt-6 flex flex-wrap gap-2" aria-label="Tecnologías utilizadas">
                                        @foreach ($project->tags as $tag)
                                            <li wire:key="project-{{ $project->id }}-tag-{{ $tag->id }}"
                                                class="rounded-full border border-cyan-300/20 bg-cyan-300/5 px-3 py-1 text-xs font-medium text-cyan-200">
                                                {{ $tag->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div class="mt-8 flex flex-1 flex-wrap items-end gap-5 text-sm font-semibold">
                                    @if ($project->repository_url)
                                        <a href="{{ $project->repository_url }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-cyan-300 transition hover:text-cyan-200">
                                            Ver código →
                                        </a>
                                    @endif

                                    @if ($project->demo_url)
                                        <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer"
                                            class="text-slate-300 transition hover:text-white">
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

                    <a href="ivanalvarez2507@gmail.com"
                        class="mt-8 inline-flex rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200">
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
