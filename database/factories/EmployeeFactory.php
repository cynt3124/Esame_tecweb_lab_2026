<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
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
            self::$currentDate = Carbon::now()->subYears(10);
        }

        self::$currentDate = self::$currentDate->copy()->addDays(rand(1, 30));

        if (self::$currentDate->greaterThan(Carbon::now())) {
            self::$currentDate = Carbon::now();
        }

        return [
        'first_name' => fake('it_IT')->firstName(),
        'middle_name' => fake()->optional()->randomLetter(),
        'last_name' => fake('it_IT')->lastName(),
        'ssn' => fake()->unique()->numerify('########'),
        'bdate' => fake()->dateTimeBetween('-63 years', '-18 years')->format('Y-m-d'),
        'address' => fake('it_IT')->streetAddress(),
        'gender' => fake()->optional()->randomElement(['M', 'F']),
        'salary' => fake()->randomFloat(2, 500, 10000),
        'department_id' => null,
        'created_at' => self::$currentDate->copy(),
        'updated_at' => self::$currentDate->copy(),
        ];
        
    }
}
