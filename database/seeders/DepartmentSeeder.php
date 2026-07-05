<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Carbon;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();

        Department::factory()->count(10)->create();

        //  Distribuisce gli employee nei department
        $departments   = Department::all();
        $shuffled       = $employees->shuffle();

        // Garantisce almeno 1 employee per department
        $departments->each(function ($department) use (&$shuffled) {
            $employee = $shuffled->shift();
            if ($employee) {
                $employee->update(['department_id' => $department->id]);
            }
        });

        // Distribuisce i rimanenti casualmente
        $shuffled->each(function ($employee) use ($departments) {
            $employee->update(['department_id' => $departments->random()->id]);
        });

         // Assegna il direttore scelto tra gli employee del department
        $departments->each(function ($department) {

            $deptEmployees = Employee::where('department_id', $department->id)->get();
            $director      = $deptEmployees->random();
            $birthDate     = Carbon::parse($director->bdate);

            // start_date >= max(18° compleanno, created_at employee, created_at department)
            $minByAge        = $birthDate->copy()->addYears(18);
            $minByEmployee   = Carbon::parse($director->created_at);
            $minByDepartment = Carbon::parse($department->created_at);

            $minDate = collect([$minByAge, $minByEmployee, $minByDepartment])->max();

            $maxDate = $birthDate->copy()->addYears(60);
            $maxDate = $maxDate->greaterThan(Carbon::now()) ? Carbon::now() : $maxDate;

            // minDate non può superare maxDate
            if ($minDate->greaterThan($maxDate)) {
                $minDate = $maxDate->copy()->subDays(1);
            }

            $department->updateQuietly([
                'director_id' => $director->id,
                'start_date'  => fake()->dateTimeBetween($minDate, $maxDate)->format('Y-m-d'),
            ]);
        });
    }
}
