<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;
use LogicException;

class ProjectForm extends Form
{
    public ?Project $project = null;

    public string $title = '';
    public string $slug = '';
    public string $summary = '';

    public string $challenge = '';
    public string $solution = '';
    public string $results = '';

    public string $repositoryUrl = '';
    public string $demoUrl = '';

    public bool $isFeatured = false;
    public bool $isPublished = false;

    public int $position = 0;

    /**
     * @var array<int, int|string>
     */
    public array $tagIds = [];

    protected function rules(): array
    {
        $slugRule = Rule::unique(Project::class, 'slug');

        if ($this->project) {
            $slugRule->ignore($this->project);
        }

        return [
            'title' => ['required', 'string', 'min:3', 'max:120'],

            'slug' => [
                'nullable',
                'string',
                'max:140',
                'alpha_dash:ascii',
                $slugRule,
            ],

            'summary' => [
                'required',
                'string',
                'min:20',
                'max:300',
            ],

            'challenge' => [
                'required',
                'string',
                'min:50',
            ],

            'solution' => [
                'required',
                'string',
                'min:50',
            ],

            'results' => [
                'nullable',
                'string',
            ],

            'repositoryUrl' => [
                'nullable',
                'url:http,https',
                'max:500',
            ],

            'demoUrl' => [
                'nullable',
                'url:http,https',
                'max:500',
            ],

            'isFeatured' => ['boolean'],
            'isPublished' => ['boolean'],

            'position' => [
                'required',
                'integer',
                'min:0',
                'max:65535',
            ],

            'tagIds' => [
                'array',
                'max:10',
            ],

            'tagIds.*' => [
                'integer',
                'distinct',
                'exists:tags,id',
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'Escribe el título.',
            'title.min' => 'El título debe contener al menos tres caracteres.',
            'title.max' => 'El título no puede superar 120 caracteres.',

            'slug.max' => 'El slug no puede superar 140 caracteres.',
            'slug.alpha_dash' => 'El slug solo puede contener letras, números, guiones y guiones bajos.',
            'slug.unique' => 'Ya existe un proyecto con este slug.',

            'summary.required' => 'Escribe el resumen.',
            'summary.min' => 'El resumen debe contener al menos 20 caracteres.',
            'summary.max' => 'El resumen no puede superar 300 caracteres.',

            'challenge.required' => 'Describe el desafío.',
            'challenge.min' => 'El desafío debe contener al menos 50 caracteres.',

            'solution.required' => 'Describe la solución.',
            'solution.min' => 'La solución debe contener al menos 50 caracteres.',

            'repositoryUrl.url' => 'La URL del repositorio no es válida.',
            'demoUrl.url' => 'La URL de demostración no es válida.',

            'position.required' => 'Escribe la posición.',
            'position.integer' => 'La posición debe ser un número entero.',
            'position.min' => 'La posición no puede ser negativa.',

            'tagIds.max' => 'Selecciona como máximo diez tecnologías.',
            'tagIds.*.exists' => 'Una de las tecnologías seleccionadas no existe.',
            'tagIds.*.distinct' => 'No puedes repetir una tecnología.',
        ];
    }

    public function setProject(Project $project): void
    {
        $this->project = $project->loadMissing('tags');

        $this->title = $project->title;
        $this->slug = $project->slug;
        $this->summary = $project->summary;

        $this->challenge = $project->challenge;
        $this->solution = $project->solution;
        $this->results = $project->results ?? '';

        $this->repositoryUrl = $project->repository_url ?? '';
        $this->demoUrl = $project->demo_url ?? '';

        $this->isFeatured = $project->is_featured;
        $this->isPublished = $project->is_published;
        $this->position = $project->position;

        $this->tagIds = $project->tags
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all();
    }

    public function store(): Project
    {
        $validated = $this->validate();

        return DB::transaction(function () use ($validated): Project {
            $project = Project::query()->create(
                $this->projectData($validated)
            );

            $project->tags()->sync(
                $validated['tagIds']
            );

            $this->reset();

            return $project;
        });
    }

    public function update(): Project
    {
        if (! $this->project) {
            throw new LogicException(
                'No se seleccionó un proyecto para actualizar.'
            );
        }

        $validated = $this->validate();

        return DB::transaction(function () use ($validated): Project {
            $this->project->update(
                $this->projectData($validated)
            );

            $this->project->tags()->sync(
                $validated['tagIds']
            );

            return $this->project->refresh();
        });
    }

    private function projectData(array $validated): array
    {
        $slug = filled($validated['slug'])
            ? Str::slug($validated['slug'])
            : $this->generateUniqueSlug($validated['title']);

        return [
            'title' => trim($validated['title']),
            'slug' => $slug,
            'summary' => trim($validated['summary']),
            'challenge' => trim($validated['challenge']),
            'solution' => trim($validated['solution']),

            'results' => filled($validated['results'])
                ? trim($validated['results'])
                : null,

            'repository_url' => filled($validated['repositoryUrl'])
                ? trim($validated['repositoryUrl'])
                : null,

            'demo_url' => filled($validated['demoUrl'])
                ? trim($validated['demoUrl'])
                : null,

            'is_featured' => $validated['isFeatured'],
            'is_published' => $validated['isPublished'],
            'position' => $validated['position'],

            'published_at' => $validated['isPublished']
                ? ($this->project?->published_at ?? now())
                : null,
        ];
    }

    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title) ?: 'proyecto';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Project::query()
                ->when(
                    $this->project,
                    fn ($query) => $query->whereKeyNot(
                        $this->project->getKey()
                    )
                )
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
