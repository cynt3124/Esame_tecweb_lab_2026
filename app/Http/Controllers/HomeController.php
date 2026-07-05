<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'chartDepEmp'    => $this->departmentEmployee(),
            'chartGrowth'    => $this->departmentEmployeeGrowth(),
            'chartGender'    => $this->employeeGender(),
            'chartProjDep'   => $this->projectsDepartment(),
            'chartProjEmp'   => $this->projectsEmployees(),
            'chartProjHours' => $this->topEmployeeProjectHours(),
        ]);
    }

    private function departmentEmployee()
    {
        return Department::withCount('employees')
            ->get()
            ->map(fn($d) => [
                'department' => $d->name,
                'count'      => $d->employees_count,
            ]);
    }

    private function departmentEmployeeGrowth()
    {
        $topDepartments = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->limit(5)
            ->get();

        $allDates        = collect();
        $departmentsData = [];

        foreach ($topDepartments as $department) {
            $employeesByDate = $department->employees()
                ->orderBy('created_at')
                ->get()
                ->groupBy(fn($e) => Carbon::parse($e->created_at)->toDateString());

            $cumulative   = [];
            $runningTotal = 0;

            foreach ($employeesByDate as $date => $employees) {
                $runningTotal     += $employees->count();
                $cumulative[$date] = $runningTotal;
                $allDates->push($date);
            }

            $departmentsData[] = [
                'department' => $department->name,
                'cumulative' => $cumulative,
            ];
        }

        $labels = $allDates->unique()->sort()->values();

        $datasets = collect($departmentsData)->map(function ($dept) use ($labels) {
            $points    = [];
            $lastValue = 0;

            foreach ($labels as $date) {
                if (isset($dept['cumulative'][$date])) {
                    $lastValue = $dept['cumulative'][$date];
                }
                $points[] = $lastValue;
            }

            return [
                'department' => $dept['department'],
                'data'       => $points,
            ];
        });

        return [
            'labels'   => $labels,
            'datasets' => $datasets,
        ];
    }

    private function employeeGender()
    {
        $data = Employee::groupBy('gender')
            ->selectRaw('gender, COUNT(*) as count')
            ->get()
            ->map(fn($e) => [
                'label' => match ($e->gender) {
                    'M'     => 'Maschio',
                    'F'     => 'Femmina',
                    default => 'Non specificato',
                },
                'count' => $e->count,
            ]);

        $total = $data->sum('count');

        return $data->map(fn($e) => [
            'label'      => $e['label'],
            'count'      => $e['count'],
            'percentage' => $total > 0 ? round(($e['count'] / $total) * 100, 1) : 0,
        ]);
    }

    // TASK 8 — Progetti per dipartimento
    private function projectsDepartment()
    {
        return Department::withCount('projects')
            ->get()
            ->map(fn($d) => [
                'department' => $d->name,
                'count'      => $d->projects_count,
            ]);
    }

    // TASK 9 — Distribuzione progetti per numero di dipendenti
    private function projectsEmployees()
    {
        return Project::withCount('employees')
            ->get()
            ->groupBy('employees_count')
            ->map(fn($group, $count) => [
                'label' => $count . ' Employee',
                'value' => $group->count(),
            ])
            ->sortKeys()
            ->values();
    }

    // TASK 10 & 11 — Top dipendente per ore
    private function topEmployeeProjectHours()
    {
        $YearAgo = Carbon::now()->subYear()->startOfMonth();

        // TASK 10 — Dipendente con più ore totali nell'ultimo anno
        $topEmployee = Employee::withSum(
            ['projects as total_hours' => function ($q) use ($YearAgo) {
                $q->whereBetween('employee_project.created_at', [$YearAgo, Carbon::now()]);
            }],
            'employee_project.hours'
        )
        ->orderByDesc('total_hours')
        ->first();

        if (! $topEmployee || ! $topEmployee->total_hours) {
            return [
                'employee'    => null,
                'total_hours' => 0,
                'labels'      => collect(),
                'datasets'    => collect(),
            ];
        }

        $topEmployee->load(['projects' => fn($q) => $q
            ->select(
                'projects.id',
                'projects.name',
                'employee_project.hours',
                'employee_project.created_at'
            )
            ->whereBetween('employee_project.created_at', [$YearAgo, Carbon::now()])
        ]);

        // TASK 11 — Asse temporale: tutti i mesi dall'anno scorso ad oggi
        $allMonths = collect();
        $cursor    = Carbon::parse($YearAgo)->startOfMonth()->copy();

        while ($cursor->lte(Carbon::now())) {
            $allMonths->push($cursor->format('m-Y'));
            $cursor->addMonth();
        }

        $datasets = $topEmployee->projects
            ->groupBy('id')
            ->map(function ($group) use ($allMonths) {
                $project = $group->first();

                $monthlyHours = $group->mapWithKeys(fn($p) => [
                    Carbon::parse($p->pivot->created_at)->format('m-Y') => $p->pivot->hours,
                ]);

                $lastValue       = 0;
                $cumulativeHours = 0;
                $data            = [];

                foreach ($allMonths as $month) {
                    if (isset($monthlyHours[$month])) {
                        $cumulativeHours += $monthlyHours[$month];
                        $lastValue        = $cumulativeHours;
                    }
                    $data[] = $lastValue;
                }

                return [
                    'label' => $project->name,
                    'data'  => $data,
                ];
            })->values();

        return [
            'employee'    => "$topEmployee->first_name $topEmployee->last_name",
            'total_hours' => $topEmployee->total_hours,
            'labels'      => $allMonths,
            'datasets'    => $datasets,
        ];
    }
}