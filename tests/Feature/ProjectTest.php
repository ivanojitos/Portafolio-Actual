<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_scope_only_returns_currently_published_projects(): void
    {
        $publishedProject = Project::factory()
            ->published()
            ->create([
                'title' => 'Proyecto visible',
            ]);

        Project::factory()->create([
            'title' => 'Proyecto borrador',
            'is_published' => false,
            'published_at' => null,
        ]);

        Project::factory()->create([
            'title' => 'Proyecto programado',
            'is_published' => true,
            'published_at' => now()->addDay(),
        ]);

        $projects = Project::query()
            ->published()
            ->get();

        $this->assertCount(1, $projects);
        $this->assertTrue($projects->first()->is($publishedProject));
    }

    public function test_projects_can_be_ordered_by_position(): void
    {
        Project::factory()->published()->create([
            'title' => 'Segundo proyecto',
            'position' => 2,
        ]);

        Project::factory()->published()->create([
            'title' => 'Primer proyecto',
            'position' => 1,
        ]);

        $projects = Project::query()
            ->published()
            ->ordered()
            ->get();

        $this->assertSame('Primer proyecto', $projects->first()->title);
    }

    public function test_published_project_can_be_viewed(): void
    {
        $project = Project::factory()
            ->published()
            ->create([
                'title' => 'Sistema público',
                'slug' => 'sistema-publico',
            ]);

        $response = $this->get(
            route('projects.show', [
                'project' => $project->slug,
            ])
        );

        $response
            ->assertOk()
            ->assertSee('Sistema público')
            ->assertSee('Caso de estudio');
    }

    public function test_draft_project_cannot_be_viewed(): void
    {
        $project = Project::factory()->create([
            'slug' => 'proyecto-borrador',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(
            route('projects.show', [
                'project' => $project->slug,
            ])
        );

        $response->assertNotFound();
    }

    public function test_scheduled_project_cannot_be_viewed_before_publication(): void
    {
        $project = Project::factory()->create([
            'slug' => 'proyecto-programado',
            'is_published' => true,
            'published_at' => now()->addWeek(),
        ]);

        $response = $this->get(
            route('projects.show', [
                'project' => $project->slug,
            ])
        );

        $response->assertNotFound();
    }
}
