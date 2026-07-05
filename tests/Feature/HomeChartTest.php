<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('charts')]
class HomeChartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    /**
     * setUp crea il dato minimo indispensabile per evitare il crash di
     * topEmployeeProjectHours(), che chiama ->load() sul risultato di ->first()
     * senza guardare se è null.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $dept = Department::factory()->create();
        $emp  = Employee::factory()->create([
            'department_id' => $dept->id,
            'gender'        => 'M',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        $proj = Project::factory()->create([
            'department_id' => $dept->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        $emp->projects()->attach($proj->id, [
            'hours'      => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function getHome(): TestResponse
    {
        return $this->actingAs($this->user)->get(route('home'));
    }

    // =========================================================================
    // VIEW — variabili presenti
    // =========================================================================

    public function test_home_returns_view_with_all_chart_variables(): void
    {
        $this->getHome()
            ->assertOk()
            ->assertViewIs('home')
            ->assertViewHasAll([
                'chartDepEmp',
                'chartGrowth',
                'chartGender',
                'chartProjDep',
                'chartProjEmp',
                'chartProjHours',
            ]);
    }

    // =========================================================================
    // chartDepEmp — dipendenti per dipartimento
    // =========================================================================

    #[Group('charts-dep-emp')]
    /*public function test_chartDepEmp_count_reflects_actual_employees(): void
    {
        $dept = Department::factory()->create();
        Employee::factory()->count(3)->create([
            'department_id' => $dept->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $data  = $this->getHome()->viewData('chartDepEmp');
        $entry = $data->firstWhere('department', $dept->name);

        $data->each(fn($item) => $this->assertArrayHasKey('department', $item));
        $data->each(fn($item) => $this->assertArrayHasKey('count', $item));
        $this->assertNotNull($entry);
        $this->assertEquals(3, $entry['count']);
    }

    #[Group('charts-dep-emp')]
    public function test_chartDepEmp_includes_department_with_zero_employees(): void
    {
        $empty = Department::factory()->create();

        $entry = $this->getHome()
            ->viewData('chartDepEmp')
            ->firstWhere('department', $empty->name);

        $this->assertNotNull($entry);
        $this->assertEquals(0, $entry['count']);
    }*/

    #[Group('charts-dep-emp')]
    public function test_chartDepEmp_includes_all_departments(): void
    {
        Department::factory()->count(3)->create();

        // setUp crea già 1 dipartimento → totale atteso: 4
        $count = $this->getHome()->viewData('chartDepEmp')->count();

        $this->assertEquals(4, $count);
    }

    // =========================================================================
    // chartGrowth — crescita cumulativa dipendenti (top 5 dipartimenti)
    // =========================================================================

    #[Group('charts-growth')]
    public function test_chartGrowth_has_labels_and_datasets_keys(): void
    {
        $data = $this->getHome()->viewData('chartGrowth');

        $this->assertArrayHasKey('labels', $data);
        $this->assertArrayHasKey('datasets', $data);
    }

    /*#[Group('charts-growth')]
    public function test_chartGrowth_datasets_limited_to_top_five(): void
    {
        foreach (range(1, 6) as $i) {
            $dept = Department::factory()->create();
            Employee::factory()->count($i)->create([
                'department_id' => $dept->id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $datasets = $this->getHome()->viewData('chartGrowth')['datasets'];

        $this->assertLessThanOrEqual(5, $datasets->count());
    }

    #[Group('charts-growth')]
    public function test_chartGrowth_each_dataset_data_length_matches_labels(): void
    {
        $data       = $this->getHome()->viewData('chartGrowth');
        $labelCount = count($data['labels']);

        foreach ($data['datasets'] as $dataset) {
            $this->assertArrayHasKey('department', $dataset);
            $this->assertArrayHasKey('data', $dataset);
            $this->assertCount($labelCount, $dataset['data'],
                "Il dataset del dipartimento '{$dataset['department']}' ha un numero di punti diverso dalle label.");
        }
    }*/

    // =========================================================================
    // chartGender — distribuzione di genere con percentuali
    // =========================================================================

    #[Group('charts-gender')]
    public function test_chartGender_maps_M_to_maschio_and_F_to_femmina(): void
    {
        Employee::factory()->create([
            'department_id' => Department::first()->id,
            'gender'        => 'F',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $labels = $this->getHome()->viewData('chartGender')->pluck('label');

        $this->assertContains('Maschio', $labels);
        $this->assertContains('Femmina', $labels);
    }

    /*#[Group('charts-gender')]
    public function test_chartGender_maps_null_gender_to_non_specificato(): void
    {
        Employee::factory()->create([
            'department_id' => Department::first()->id,
            'gender'        => null,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $labels = $this->getHome()->viewData('chartGender')->pluck('label');

        $this->assertContains('Non specificato', $labels);
    }

    #[Group('charts-gender')]
    public function test_chartGender_percentages_sum_to_100(): void
    {
        $total = $this->getHome()->viewData('chartGender')->sum('percentage');

        $this->assertEqualsWithDelta(100.0, $total, 0.2);
    }*/

    // =========================================================================
    // chartProjDep — progetti per dipartimento
    // =========================================================================

    #[Group('charts-proj-dep')]
    public function test_chartProjDep_count_reflects_actual_projects(): void
    {
        $dept = Department::factory()->create();
        Project::factory()->count(2)->create([
            'department_id' => $dept->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $data  = $this->getHome()->viewData('chartProjDep');
        $entry = $data->firstWhere('department', $dept->name);

        $data->each(fn($item) => $this->assertArrayHasKey('department', $item));
        $data->each(fn($item) => $this->assertArrayHasKey('count', $item));
        $this->assertNotNull($entry);
        $this->assertEquals(2, $entry['count']);
    }

    #[Group('charts-proj-dep')]
    public function test_chartProjDep_includes_department_with_zero_projects(): void
    {
        $empty = Department::factory()->create();

        $entry = $this->getHome()
            ->viewData('chartProjDep')
            ->firstWhere('department', $empty->name);

        $this->assertNotNull($entry);
        $this->assertEquals(0, $entry['count']);
    }

    #[Group('charts-proj-dep')]
    public function test_chartProjDep_includes_all_departments(): void
    {
        Department::factory()->count(3)->create();

        // setUp crea già 1 dipartimento → totale atteso: 4
        $count = $this->getHome()->viewData('chartProjDep')->count();

        $this->assertEquals(4, $count);
    }

    // =========================================================================
    // chartProjEmp — progetti raggruppati per numero di dipendenti assegnati
    // =========================================================================

    #[Group('charts-proj-emp')]
    public function test_chartProjEmp_groups_projects_by_employee_count(): void
    {
        $dept      = Department::first();
        $employees = Employee::factory()->count(2)->create([
            'department_id' => $dept->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $proj = Project::factory()->create([
            'department_id' => $dept->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        $employees->each(fn($e) => $e->projects()->attach($proj->id, ['hours' => 5]));

        $data = $this->getHome()->viewData('chartProjEmp');

        $data->each(function ($item) {
            $this->assertArrayHasKey('label', $item);
            $this->assertArrayHasKey('value', $item);
        });

        $entry = $data->firstWhere('label', '2 Employee');
        $this->assertNotNull($entry, "Nessun gruppo '2 Employee' trovato in chartProjEmp.");
    }

    #[Group('charts-proj-emp')]
    public function test_chartProjEmp_project_with_no_employees_has_zero_employee_label(): void
    {
        // setUp ha già 1 progetto con 1 dipendente; aggiungo 1 senza dipendenti
        Project::factory()->create([
            'department_id' => Department::first()->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $entry = $this->getHome()
            ->viewData('chartProjEmp')
            ->firstWhere('label', '0 Employee');

        $this->assertNotNull($entry, "Nessun gruppo '0 Employee' trovato in chartProjEmp.");
    }

    #[Group('charts-proj-emp')]
    public function test_chartProjEmp_aggregates_and_sorts_ascending(): void
    {
        $dept = Department::first();
        $emp  = Employee::factory()->create(['department_id' => $dept->id, 'created_at' => now(), 'updated_at' => now()]);

        // Creo 2 progetti con 1 dipendente ciascuno → il gruppo "1 Employee" deve avere value >= 2
        foreach (range(1, 2) as $i) {
            $proj = Project::factory()->create(['department_id' => $dept->id, 'created_at' => now(), 'updated_at' => now()]);
            $emp->projects()->attach($proj->id, ['hours' => 5]);
        }

        $data = $this->getHome()->viewData('chartProjEmp');

        // Verifica aggregazione: "1 Employee" conta almeno 2 progetti
        $group = $data->firstWhere('label', '1 Employee');
        $this->assertNotNull($group);
        $this->assertGreaterThanOrEqual(2, $group['value']);

        // Verifica ordinamento crescente per numero di dipendenti
        $labels = $data->pluck('label')->map(fn($l) => (int) $l)->values();
        $this->assertEquals($labels->sort()->values()->all(), $labels->all());
    }

    // =========================================================================
    // chartProjHours — top dipendente per ore nell'ultimo anno
    // =========================================================================

    #[Group('charts-proj-hours')]
    public function test_chartProjHours_has_correct_structure(): void
    {
        $data = $this->getHome()->viewData('chartProjHours');

        $this->assertArrayHasKey('employee', $data);
        $this->assertArrayHasKey('total_hours', $data);
        $this->assertArrayHasKey('labels', $data);
        $this->assertArrayHasKey('datasets', $data);

        foreach ($data['datasets'] as $dataset) {
            $this->assertArrayHasKey('label', $dataset);
            $this->assertArrayHasKey('data', $dataset);
        }
    }

    #[Group('charts-proj-hours')]
    public function test_chartProjHours_selects_employee_with_most_hours(): void
    {
        $dept = Department::factory()->create();
        $proj = Project::factory()->create([
            'department_id' => $dept->id,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $topEmp = Employee::factory()->create(['department_id' => $dept->id, 'created_at' => now(), 'updated_at' => now()]);
        $topEmp->projects()->attach($proj->id, ['hours' => 100, 'created_at' => now(), 'updated_at' => now()]);

        $lowEmp = Employee::factory()->create(['department_id' => $dept->id, 'created_at' => now(), 'updated_at' => now()]);
        $lowEmp->projects()->attach($proj->id, ['hours' => 5, 'created_at' => now(), 'updated_at' => now()]);

        $data = $this->getHome()->viewData('chartProjHours');

        $this->assertEquals("{$topEmp->first_name} {$topEmp->last_name}", $data['employee']);
    }

    #[Group('charts-proj-hours')]
    public function test_chartProjHours_labels_cover_thirteen_months(): void
    {
        $data = $this->getHome()->viewData('chartProjHours');

        $this->assertCount(13, $data['labels']);
    }

    #[Group('charts-proj-hours')]
    public function test_chartProjHours_dataset_data_length_matches_labels(): void
    {
        $data       = $this->getHome()->viewData('chartProjHours');
        $labelCount = count($data['labels']);

        foreach ($data['datasets'] as $dataset) {
            $this->assertCount($labelCount, $dataset['data'],
                "Il dataset del progetto '{$dataset['label']}' ha un numero di punti diverso dalle label.");
        }
    }
}
