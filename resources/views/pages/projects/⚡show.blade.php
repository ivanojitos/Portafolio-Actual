<?php

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        abort_unless(
            $project->isPubliclyVisible(),
            404
        );

        $this->project = $project->load([
            'tags:id,name,slug,color',
        ]);
    }
};

?>

<div>
    <header class="border-b border-white/10">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5">
            <a
                href="{{ route('home') }}"
                wire:navigate
                class="font-bold text-white transition hover:text-cyan-300"
            >
                Mi Portafolio
            </a>

            <a
                href="{{ route('home') }}#proyectos"
                class="text-sm text-slate-300 transition hover:text-cyan-300"
            >
                ← Volver a proyectos
            </a>
        </nav>
    </header>

    <main>
        <article class="mx-auto max-w-5xl px-6 py-20">
            <div class="max-w-3xl">
                <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                    Caso de estudio
                </p>

                <h1 class="mt-6 text-4xl font-black tracking-tight text-white sm:text-6xl">
                    {{ $project->title }}
                </h1>

                <p class="mt-8 text-xl leading-9 text-slate-300">
                    {{ $project->summary }}
                </p>

                @if ($project->tags->isNotEmpty())
                    <ul class="mt-8 flex flex-wrap gap-2">
                        @foreach ($project->tags as $tag)
                            <li
                                wire:key="tag-{{ $tag->id }}"
                                class="rounded-full border border-cyan-300/20 bg-cyan-300/5 px-3 py-1 text-sm text-cyan-200"
                            >
                                {{ $tag->name }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="mt-16 grid gap-8 lg:grid-cols-3">
                <section class="rounded-2xl border border-white/10 bg-slate-900/50 p-7">
                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-300">
                        01 · Desafío
                    </p>

                    <h2 class="mt-4 text-2xl font-bold text-white">
                        El problema
                    </h2>

                    <p class="mt-5 whitespace-pre-line leading-8 text-slate-300">{{ $project->challenge }}</p>
                </section>

                <section class="rounded-2xl border border-white/10 bg-slate-900/50 p-7">
                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-300">
                        02 · Solución
                    </p>

                    <h2 class="mt-4 text-2xl font-bold text-white">
                        La implementación
                    </h2>

                    <p class="mt-5 whitespace-pre-line leading-8 text-slate-300">{{ $project->solution }}</p>
                </section>

                <section class="rounded-2xl border border-white/10 bg-slate-900/50 p-7">
                    <p class="font-mono text-xs font-semibold uppercase tracking-widest text-cyan-300">
                        03 · Resultado
                    </p>

                    <h2 class="mt-4 text-2xl font-bold text-white">
                        El impacto
                    </h2>

                    <p class="mt-5 whitespace-pre-line leading-8 text-slate-300">
                        {{ $project->results ?: 'Los resultados se documentarán próximamente.' }}
                    </p>
                </section>
            </div>

            @if ($project->repository_url || $project->demo_url)
                <div class="mt-12 flex flex-wrap gap-4 border-t border-white/10 pt-10">
                    @if ($project->repository_url)
                        <a
                            href="{{ $project->repository_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200"
                        >
                            Ver repositorio
                        </a>
                    @endif

                    @if ($project->demo_url)
                        <a
                            href="{{ $project->demo_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-xl border border-white/20 px-6 py-3 font-semibold text-white transition hover:border-cyan-300 hover:text-cyan-300"
                        >
                            Abrir demostración
                        </a>
                    @endif
                </div>
            @endif
        </article>
    </main>
</div>
