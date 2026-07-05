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
#[Group('employee-project')]
class EmployeeProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function actingAsUser(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    private function makeEmployee(?Department $department = null): Employee
    {
        $department ??= Department::factory()->create();
        return Employee::factory()->create([
            'department_id' => $department->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    private function makeProject(?Department $department = null): Project
    {
        $department ??= Department::factory()->create();
        return Project::factory()->create([
            'department_id' => $department->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    private function attachPivot(Employee $employee, Project $project, int $hours = 10): void
    {
        $employee->projects()->attach($project->id, [
            'hours'      => $hours,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // =========================================================================
    // STORE
    // =========================================================================

    #[Group('store')]
    public function test_store_attaches_employee_to_project_with_hours(): void
    {
        $employee = $this->makeEmployee();
        $project  = $this->makeProject();

        $this->actingAsUser()->post(route('employee-project.store'), [
            'employee_id' => $employee->id,
            'project_id'  => $project->id,
            'hours'       => 25,
        ])->assertRedirect(route('employee-project.index'));

        $this->assertDatabaseHas('employee_project', [
            'employee_id' => $employee->id,
            'project_id'  => $project->id,
            'hours'       => 25,
        ]);
    }

    #[Group('store')]
    #[Group('validation')]
    public function test_store_fails_when_employee_id_is_missing(): void
    {
        $this->actingAsUser()->post(route('employee-project.store'), [
            'project_id' => $this->makeProject()->id,
            'hours'      => 10,
        ])->assertSessionHasErrors('employee_id');
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    #[Group('update')]
    public function test_update_modifies_hours_on_existing_pivot(): void
    {
        $employee = $this->makeEmployee();
        $project  = $this->makeProject();
        $this->attachPivot($employee, $project, 10);

        $this->actingAsUser()->put(
            route('employee-project.update', [
                'employee' => $employee->id,
                'project'  => $project->id,
            ]),
            ['hours' => 50]
        )->assertRedirect(route('employee-project.index'));

        $this->assertDatabaseHas('employee_project', [
            'employee_id' => $employee->id,
            'project_id'  => $project->id,
            'hours'       => 50,
        ]);
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    #[Group('destroy')]
    public function test_destroy_detaches_pivot_and_redirects(): void
    {
        $employee = $this->makeEmployee();
        $project  = $this->makeProject();
        $this->attachPivot($employee, $project);

        $this->actingAsUser()
            ->delete(route('employee-project.destroy', [
                'employee' => $employee->id,
                'project'  => $project->id,
            ]))
            ->assertRedirect(route('employee-project.index'));

        $this->assertDatabaseMissing('employee_project', [
            'employee_id' => $employee->id,
            'project_id'  => $project->id,
        ]);
    }
}
