<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('ajax')]
#[Group('employee-project')]
class EmployeeProjectAjaxStoreTest extends TestCase
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

    /** Header che attiva $request->ajax() nel controller e forza JSON in risposta. */
    private function ajaxHeaders(): array
    {
        return [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept'           => 'application/json',
        ];
    }

    // =========================================================================
    // AJAX STORE
    // =========================================================================

    /** 1. Risposta 200 con success = true */
    #[Group('store')]
    public function test_ajax_store_returns_200_json_with_success_true(): void
    {
        $employee = $this->makeEmployee();
        $project  = $this->makeProject();

        $this->actingAsUser()
            ->withHeaders($this->ajaxHeaders())
            ->post(route('employee-project.store'), [
                'employee_id' => $employee->id,
                'project_id'  => $project->id,
                'hours'       => 20,
            ])
            ->assertOk()
            ->assertJson(['success' => true]);
    }

    /** 2. Il payload data contiene employee_name, project_name e hours */
    #[Group('store')]
    public function test_ajax_store_response_data_contains_employee_project_and_hours(): void
    {
        $employee = $this->makeEmployee();
        $project  = $this->makeProject();

        $response = $this->actingAsUser()
            ->withHeaders($this->ajaxHeaders())
            ->post(route('employee-project.store'), [
                'employee_id' => $employee->id,
                'project_id'  => $project->id,
                'hours'       => 30,
            ])
            ->assertOk();

        $data = $response->json('data');

        $this->assertArrayHasKey('employee_name', $data);
        $this->assertArrayHasKey('project_name',  $data);
        $this->assertArrayHasKey('hours',         $data);
        $this->assertEquals(30, $data['hours']);
        $this->assertEquals($project->name, $data['project_name']);
    }

    /** 3. Il pivot viene effettivamente salvato nel database */
    #[Group('store')]
    public function test_ajax_store_attaches_pivot_to_database(): void
    {
        $employee = $this->makeEmployee();
        $project  = $this->makeProject();

        $this->actingAsUser()
            ->withHeaders($this->ajaxHeaders())
            ->post(route('employee-project.store'), [
                'employee_id' => $employee->id,
                'project_id'  => $project->id,
                'hours'       => 15,
            ])
            ->assertOk();

        $this->assertDatabaseHas('employee_project', [
            'employee_id' => $employee->id,
            'project_id'  => $project->id,
            'hours'       => 15,
        ]);
    }

    /** 4. Validazione: employee_id mancante → 422 con errori JSON */
    #[Group('store')]
    #[Group('validation')]
    public function test_ajax_store_returns_422_when_employee_id_is_missing(): void
    {
        $this->actingAsUser()
            ->withHeaders($this->ajaxHeaders())
            ->post(route('employee-project.store'), [
                'project_id' => $this->makeProject()->id,
                'hours'      => 10,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('employee_id');
    }
}
