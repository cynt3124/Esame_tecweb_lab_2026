<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake('it_IT')->unique()->bs(),
            'site_name' => fake('it_IT')->city(),
            'department_id' => null,
            'created_at'    => null,
            'updated_at'    => null,
        ];
    }

    // Collega gli Employee (default 3) con le ore ai Project
    public function withEmployees(Collection $employees, int $count = 3): static
    {
        return $this->afterCreating(function (Project $project) use ($employees, $count) {
            $assigned = $employees->random($count);

            $maxEmployeeDate = $assigned->max(fn($emp) => Carbon::parse($emp->created_at));
            $projectDate = Carbon::parse($project->created_at);

            $pivotDate = $projectDate->greaterThan($maxEmployeeDate) 
                ? $projectDate->copy() 
                : Carbon::parse($maxEmployeeDate);

            $project->employees()->attach(
            $assigned->mapWithKeys(function ($emp) use ($pivotDate) {
                
                $pivotDate = $pivotDate->copy()->addDays(rand(1, 29));

                if ($pivotDate->greaterThan(Carbon::now())) {
                    $pivotDate = Carbon::now();
                }

                return [
                    $emp->id => [
                        'hours' => fake()->numberBetween(5, 60),
                        'created_at' => $pivotDate->copy(),
                        'updated_at' => $pivotDate->copy(),
                    ]
                ];
            })->toArray()
            );
        });
    }
    
}
