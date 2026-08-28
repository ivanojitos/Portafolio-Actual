<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ProjectForm extends Form
{
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
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:120',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:140',
                'alpha_dash:ascii',
                Rule::unique(Project::class, 'slug'),
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

            'isFeatured' => [
                'boolean',
            ],

            'isPublished' => [
                'boolean',
            ],

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

    public function store(): Project
    {
        $validated = $this->validate();

        $slug = filled($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        $project = Project::query()->create([
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
            'cover_image' => null,
            'is_featured' => $validated['isFeatured'],
            'is_published' => $validated['isPublished'],
            'position' => $validated['position'],
            'published_at' => $validated['isPublished']
                ? now()
                : null,
        ]);

        $project->tags()->sync(
            $validated['tagIds']
        );

        $this->reset();

        return $project;
    }
}
