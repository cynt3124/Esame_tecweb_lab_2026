<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        $employees   = Employee::all();
        $maxDate     = Carbon::now();

        $totalProjects = rand(40, 60);
        $extraProjects = $totalProjects - $departments->count();

        // Genera 1 data garantita per ogni department
        $guaranteedDates = $departments->map(function($dept) use ($maxDate) {
            $deptDate = Carbon::parse($dept->created_at)->addDays(rand(1, 20));

            $projectDate = $deptDate->greaterThanOrEqualTo($maxDate)
                ? $maxDate->copy()
                : Carbon::parse(fake()->dateTimeBetween($deptDate, $maxDate));

            return [
                'date'       => $projectDate,
                'department' => $dept,
                'guaranteed' => true,
            ];
        });

        // Genera le date extra casuali sull'intero range
        $minDate    = Carbon::parse($departments->min('created_at'));
        $extraDates = collect(range(1, $extraProjects))
            ->map(fn() => [
                'date'       => Carbon::parse(fake()->dateTimeBetween($minDate, $maxDate)),
                'department' => null,
                'guaranteed' => false,
            ]);

        $allProjects = $guaranteedDates
            ->concat($extraDates)
            ->sortBy('date')
            ->values();

        foreach ($allProjects as $item) {

            $projectDate = $item['date'];

            if ($item['guaranteed']) {
                $department = $item['department'];
            } else {
                $eligibleDepts = $departments->filter(
                    fn($dept) => Carbon::parse($dept->created_at)
                        ->lessThanOrEqualTo($projectDate)
                );

                // Se nessun department può essere utilizzato usa il più vecchio
                $department = $eligibleDepts->isNotEmpty()
                    ? $eligibleDepts->random()
                    : $departments->sortBy('created_at')->first();
            }

            Project::factory()
                ->withEmployees($employees, rand(2, 5))
                ->create([
                    'department_id' => $department->id,
                    'created_at'    => $projectDate->copy(),
                    'updated_at'    => $projectDate->copy(),
                ]);
        }
    }
}
