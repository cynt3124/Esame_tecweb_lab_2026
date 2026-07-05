<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('projects')]
class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function actingAsUser(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    private function makeDepartment(): Department
    {
        return Department::factory()->create();
    }

    private function makeProject(?Department $department = null): Project
    {
        $department ??= $this->makeDepartment();
        return Project::factory()->create([
            'department_id' => $department->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    // =========================================================================
    // INDEX
    // =========================================================================

    public function test_index_returns_view_with_projects(): void
    {
        $department = $this->makeDepartment();
        Project::factory()->count(3)->create([
            'department_id' => $department->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->actingAsUser()
            ->get(route('projects.index'))
            ->assertOk()
            ->assertViewIs('projects.index')
            ->assertViewHas('projects');
    }


    // =========================================================================
    // STORE
    // =========================================================================

    #[Group('store')]
    public function test_store_creates_project_with_valid_data(): void
    {
        $department = $this->makeDepartment();

        $this->actingAsUser()->post(route('projects.store'), [
            'name'          => 'Progetto Alpha',
            'site_name'     => 'Milano',
            'department_id' => $department->id,
        ])->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'name'          => 'Progetto Alpha',
            'site_name'     => 'Milano',
            'department_id' => $department->id,
        ]);
    }

    #[Group('store')]
    #[Group('validation')]
    public function test_store_fails_when_name_is_missing(): void
    {
        $this->actingAsUser()->post(route('projects.store'), [
            'department_id' => $this->makeDepartment()->id,
        ])->assertSessionHasErrors('name');
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    #[Group('update')]
    public function test_update_modifies_project_with_valid_data(): void
    {
        $project = $this->makeProject();
        $newDept = $this->makeDepartment();

        $this->actingAsUser()->put(route('projects.update', $project), [
            'name'          => 'Nome Aggiornato',
            'site_name'     => 'Roma',
            'department_id' => $newDept->id,
        ])->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'id'            => $project->id,
            'name'          => 'Nome Aggiornato',
            'site_name'     => 'Roma',
            'department_id' => $newDept->id,
        ]);
    }

    #[Group('update')]
    #[Group('validation')]
    public function test_update_fails_when_name_is_missing(): void
    {
        $project = $this->makeProject();

        $this->actingAsUser()->put(route('projects.update', $project), [
            'department_id' => $project->department_id,
        ])->assertSessionHasErrors('name');
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    #[Group('destroy')]
    public function test_destroy_deletes_project_and_redirects(): void
    {
        $project = $this->makeProject();

        $this->actingAsUser()
            ->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

}
