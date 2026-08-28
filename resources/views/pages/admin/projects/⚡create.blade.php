<?php

use App\Livewire\Forms\ProjectForm;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts::app')] #[Title('Nuevo proyecto')] class extends Component {
    public ProjectForm $form;
    public ?Project $project = null;

    public function mount(?Project $project = null): void
    {
        $this->project = $project;

        if ($project) {
            Gate::authorize('update', $project);
            $this->form->setProject($project);

            return;
        }

        Gate::authorize('create', Project::class);
    }

    #[Computed]
    public function tags()
    {
        return Tag::query()->orderBy('name')->get();
    }

    public function save(): void
    {
        if ($this->project) {
            Gate::authorize('update', $this->project);

            $project = $this->form->update();
            $message = "El proyecto {$project->title} fue actualizado correctamente.";
        } else {
            Gate::authorize('create', Project::class);

            $project = $this->form->store();
            $message = "El proyecto {$project->title} fue creado correctamente.";
        }

        session()->flash('success', $message);

        $this->redirectRoute('admin.projects.index', navigate: true);
    }
};

?>

<div class="min-h-screen">
    <livewire:admin-navigation />

    <main class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <a href="{{ route('admin.projects.index') }}" wire:navigate
            class="text-sm text-slate-400 transition hover:text-cyan-300">
            ← Volver a proyectos
        </a>

        <div class="mt-8">
            <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                Contenido
            </p>

            <h1 class="mt-4 text-4xl font-bold text-white">
                {{ $project ? 'Editar proyecto' : 'Nuevo proyecto' }}
            </h1>

            <p class="mt-3 text-slate-400">
                Crea un caso de estudio que explique el problema, la solución y el resultado.
            </p>
        </div>

        <form wire:submit="save" class="mt-10 space-y-8">
            <section class="rounded-2xl border border-white/10 bg-slate-900/60 p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">
                    Información principal
                </h2>

                <div class="mt-6 grid gap-6">
                    <div>
                        <label for="project-title" class="text-sm font-medium text-slate-200">
                            Título
                        </label>

                        <input id="project-title" type="text" wire:model.blur="form.title"
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300">

                        @error('form.title')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project-slug" class="text-sm font-medium text-slate-200">
                            Slug <span class="text-slate-500">(opcional)</span>
                        </label>

                        <input id="project-slug" type="text" wire:model.blur="form.slug"
                            placeholder="Se genera automáticamente"
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none placeholder:text-slate-600 focus:border-cyan-300">

                        @error('form.slug')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project-summary" class="text-sm font-medium text-slate-200">
                            Resumen
                        </label>

                        <textarea id="project-summary" wire:model.blur="form.summary" rows="3" maxlength="300"
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300"></textarea>

                        @error('form.summary')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-white/10 bg-slate-900/60 p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">
                    Caso de estudio
                </h2>

                <div class="mt-6 grid gap-6">
                    <div>
                        <label for="project-challenge" class="text-sm font-medium text-slate-200">
                            Desafío
                        </label>

                        <textarea id="project-challenge" wire:model.blur="form.challenge" rows="6"
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300"></textarea>

                        @error('form.challenge')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project-solution" class="text-sm font-medium text-slate-200">
                            Solución
                        </label>

                        <textarea id="project-solution" wire:model.blur="form.solution" rows="6"
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300"></textarea>

                        @error('form.solution')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project-results" class="text-sm font-medium text-slate-200">
                            Resultados <span class="text-slate-500">(opcional)</span>
                        </label>

                        <textarea id="project-results" wire:model.blur="form.results" rows="5"
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300"></textarea>

                        @error('form.results')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-white/10 bg-slate-900/60 p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">
                    Enlaces y tecnologías
                </h2>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="repository-url" class="text-sm font-medium text-slate-200">
                            Repositorio
                        </label>

                        <input id="repository-url" type="url" wire:model.blur="form.repositoryUrl"
                            placeholder="https://github.com/..."
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none placeholder:text-slate-600 focus:border-cyan-300">

                        @error('form.repositoryUrl')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="demo-url" class="text-sm font-medium text-slate-200">
                            Demostración
                        </label>

                        <input id="demo-url" type="url" wire:model.blur="form.demoUrl" placeholder="https://..."
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none placeholder:text-slate-600 focus:border-cyan-300">

                        @error('form.demoUrl')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <fieldset class="mt-8">
                    <legend class="text-sm font-medium text-slate-200">
                        Tecnologías
                    </legend>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2 md:grid-cols-3">
                        @foreach ($this->tags as $tag)
                            <label wire:key="select-tag-{{ $tag->id }}"
                                class="flex items-center gap-3 rounded-xl border border-white/10 bg-slate-950 p-3 text-sm text-slate-300">
                                <input type="checkbox" value="{{ $tag->id }}" wire:model="form.tagIds"
                                    class="rounded border-white/20 bg-slate-900 text-cyan-300 focus:ring-cyan-300">

                                {{ $tag->name }}
                            </label>
                        @endforeach
                    </div>

                    @error('form.tagIds')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror

                    @error('form.tagIds.*')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </fieldset>
            </section>

            <section class="rounded-2xl border border-white/10 bg-slate-900/60 p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">
                    Publicación
                </h2>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="project-position" class="text-sm font-medium text-slate-200">
                            Posición
                        </label>

                        <input id="project-position" type="number" min="0" max="65535"
                            wire:model="form.position"
                            class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white outline-none focus:border-cyan-300">

                        @error('form.position')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-4 pt-2 sm:pt-8">
                        <label class="flex items-center gap-3 text-sm text-slate-300">
                            <input type="checkbox" wire:model="form.isFeatured"
                                class="rounded border-white/20 bg-slate-950 text-cyan-300 focus:ring-cyan-300">

                            Mostrar como proyecto destacado
                        </label>

                        <label class="flex items-center gap-3 text-sm text-slate-300">
                            <input type="checkbox" wire:model="form.isPublished"
                                class="rounded border-white/20 bg-slate-950 text-cyan-300 focus:ring-cyan-300">

                            Publicar inmediatamente
                        </label>
                    </div>
                </div>
            </section>

            <div class="flex flex-wrap justify-end gap-4">
                <a href="{{ route('admin.projects.index') }}" wire:navigate
                    class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-300 transition hover:border-white/30 hover:text-white">
                    Cancelar
                </a>

                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="rounded-xl bg-cyan-300 px-6 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200 disabled:opacity-60">
                    <span wire:loading.remove wire:target="save">
                        {{ $project ? 'Actualizar proyecto' : 'Guardar proyecto' }}
                    </span>

                    <span wire:loading wire:target="save">
                        Guardando...
                    </span>
                </button>
            </div>
        </form>
    </main>
</div>
