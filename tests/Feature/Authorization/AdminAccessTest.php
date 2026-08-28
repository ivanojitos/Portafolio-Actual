<?php

namespace Tests\Feature\Authorization;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(
            route('admin.dashboard')
        );

        $response->assertRedirect(
            route('login')
        );
    }

    public function test_regular_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_regular_user_cannot_access_project_administration(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.projects.index'));

        $response->assertForbidden();
    }

    public function test_administrator_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Panel administrativo');
    }

    public function test_administrator_can_access_project_administration(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.projects.index'));

        $response
            ->assertOk()
            ->assertSee('Proyectos');
    }

    public function test_projects_cannot_be_permanently_deleted(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $project = Project::factory()->create();

        $this->assertFalse(
            $admin->can('forceDelete', $project)
        );
    }
}
