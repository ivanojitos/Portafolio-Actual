<?php

use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] #[Title('Administrar proyectos')] class extends Component {
    use WithPagination;

    #[Url(as: 'buscar', except: '')]
    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Project::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function projects()
    {
        return Project::query()
            ->with(['tags:id,name,slug'])
            ->when(
                filled($this->search),
                fn($query) => $query->where(function ($query): void {
                    $query->where('title', 'like', '%' . $this->search . '%')->orWhere('summary', 'like', '%' . $this->search . '%');
                }),
            )
            ->orderBy('position')
            ->orderByDesc('created_at')
            ->paginate(10);
    }
};

?>

<div class="min-h-screen">
    <livewire:admin-navigation />

    <main class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <div>
                <p class="font-mono text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                    Contenido
                </p>

                <h1 class="mt-4 text-4xl font-bold text-white">
                    Proyectos
                </h1>

                <p class="mt-3 text-slate-400">
                    Administra los casos de estudio del portafolio.
                </p>
            </div>

            <a href="{{ route('admin.projects.create') }}" wire:navigate
                class="rounded-xl bg-cyan-300 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-200">
                Nuevo proyecto
            </a>
        </div>

        <div class="mt-10">
            <label for="project-search" class="sr-only">
                Buscar proyectos
            </label>

            <input id="project-search" type="search" wire:model.live.debounce.300ms="search"
                placeholder="Buscar por título o descripción..."
                class="w-full max-w-xl rounded-xl border border-white/10 bg-slate-900 px-4 py-3 text-white outline-none placeholder:text-slate-600 focus:border-cyan-300">
        </div>

        <div class="mt-8 overflow-hidden rounded-2xl border border-white/10">
            @if ($this->projects->isEmpty())
                <div class="p-10 text-center text-slate-400">
                    No se encontraron proyectos.
                </div>
            @else
                <div class="overflow-x-auto">

                    @if (session('success'))
                        <div class="mt-8 rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm text-emerald-200"
                            role="status">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-slate-900/80">
                            <tr class="text-left text-xs uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-4">Proyecto</th>
                                <th class="px-6 py-4">Tecnologías</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4">Orden</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/10 bg-slate-950/50">
                            @foreach ($this->projects as $project)
                                <tr wire:key="admin-project-{{ $project->id }}">
                                    <td class="px-6 py-5">
                                        <p class="font-semibold text-white">
                                            {{ $project->title }}
                                        </p>

                                        <p class="mt-1 max-w-md truncate text-sm text-slate-500">
                                            {{ $project->summary }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex max-w-xs flex-wrap gap-2">
                                            @foreach ($project->tags as $tag)
                                                <span
                                                    class="rounded-full bg-white/5 px-2.5 py-1 text-xs text-slate-300">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($project->is_published)
                                            <span
                                                class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                                                Publicado
                                            </span>
                                        @else
                                            <span
                                                class="rounded-full bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-300">
                                                Borrador
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-400">
                                        {{ $project->position }}
                                    </td>

                                    <td class="px-6 py-5 text-right">

                                        @if ($project->isPubliclyVisible())
                                            <a href="{{ route('admin.projects.edit', [
                                                'project' => $project->slug,
                                            ]) }}"
                                                wire:navigate
                                                class="mr-4 text-sm font-semibold text-slate-300 hover:text-white">
                                                Editar
                                            </a>
                                            <a href="{{ route('projects.show', ['project' => $project->slug]) }}"
                                                target="_blank" rel="noopener noreferrer"
                                                class="text-sm font-semibold text-cyan-300 hover:text-cyan-200">
                                                Ver ↗
                                            </a>
                                        @else
                                            <span class="text-sm text-slate-600">
                                                No visible
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-white/10 bg-slate-900/40 px-6 py-4">
                    {{ $this->projects->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
