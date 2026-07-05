<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('projects')]
#[Group('models')]
class ProjectModelTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Relazioni
    // =========================================================================

    public function test_project_belongs_to_department(): void
    {
        $department = Department::factory()->create();
        $project    = Project::factory()->create(['department_id' => $department->id]);

        $this->assertInstanceOf(Department::class, $project->department);
        $this->assertEquals($department->id, $project->department->id);
    }

    public function test_project_has_many_employees_through_pivot(): void
    {
        $department = Department::factory()->create();
        $project    = Project::factory()->create(['department_id' => $department->id]);
        $employees  = Employee::factory()->count(3)->create(['department_id' => $department->id]);

        foreach ($employees as $employee) {
            $employee->projects()->attach($project->id, ['hours' => 20]);
        }

        $this->assertCount(3, $project->fresh()->employees);
    }

    public function test_project_employee_pivot_contains_hours(): void
    {
        $department = Department::factory()->create();
        $project    = Project::factory()->create(['department_id' => $department->id]);
        $employee   = Employee::factory()->create(['department_id' => $department->id]);

        $employee->projects()->attach($project->id, ['hours' => 42]);

        $this->assertEquals(42, $project->fresh()->employees->first()->pivot->hours);
    }

    // =========================================================================
    // Cascade delete
    // =========================================================================

    #[Group('destroy')]
    public function test_deleting_department_cascades_to_projects(): void
    {
        $department = Department::factory()->create();
        $project    = Project::factory()->create(['department_id' => $department->id]);

        $department->delete();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
