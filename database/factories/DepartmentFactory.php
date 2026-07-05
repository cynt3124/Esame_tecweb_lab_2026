<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;


/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    private static ?Carbon $currentDate = null;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (is_null(self::$currentDate)) {
            self::$currentDate = Carbon::now()->subYears(20);
        }

        self::$currentDate = self::$currentDate->copy()->addDays(rand(1,10))->addYears(rand(1, 3));

        if (self::$currentDate->greaterThan(Carbon::now())) {
            self::$currentDate = Carbon::now();
        }

        return [
            'name' => fake('it_IT')->unique()->company(),
            'start_date' => null,
            'director_id' => null,
            'created_at' => self::$currentDate->copy(),
            'updated_at' => self::$currentDate->copy(),
        ];
    }
}
