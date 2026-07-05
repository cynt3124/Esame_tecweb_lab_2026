<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $employees   = Employee::select('id', 'first_name', 'middle_name', 'last_name')->get();
        $departments = Department::with('director')->paginate(10);

        return view('departments.index', compact('departments', 'employees'));
    }

    public function create()
    {
        $employees = Employee::select('id', 'first_name', 'middle_name', 'last_name')->get();

        return view('departments.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|min:3',
            'start_date'  => 'nullable|date',
            'director_id' => 'nullable|exists:employees,id|unique:departments,director_id',
        ]);

        $input = $request->all();

        if ($request->ajax()) {
            $new_dep = Department::create($input);
            $new_dep->load('director');

            return response()->json([
                'success' => true,
                'message' => 'Department creato con successo!',
                'data'    => [
                    'id'         => $new_dep->id,
                    'name'       => $new_dep->name,
                    'start_date' => $new_dep->start_date
                                        ? $new_dep->start_date->format('d/m/Y')
                                        : '',
                    'director'   => $new_dep->director
                                        ? trim($new_dep->director->first_name . ' ' . $new_dep->director->middle_name . ' ' . $new_dep->director->last_name)
                                        : 'N/D',
                    'created_at' => now()->format('d/m/Y'),
                    'updated_at' => now()->format('d/m/Y'),
                ],
            ], 200);
        }

        try {
            Department::create($input);

            return redirect(route('departments.index'));

        } catch (\Exception $e) {

            return back()->with('error', 'Errore durante il salvataggio dei dati.');
        }
    }

    public function show(Department $department)
    {
        //
    }

    public function edit(Department $department)
    {
        $department->load('employees');

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'        => 'required',
            'start_date'  => 'nullable|date',
            'director_id' => 'required|exists:employees,id|unique:departments,director_id',
        ]);

        $department->update($request->all());

        return redirect(route('departments.index'));
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect(route('departments.index'));
    }
}