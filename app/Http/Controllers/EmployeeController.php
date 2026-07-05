<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$employees = Employee::all();
        //$employees = Employee::with('department')->get();
        $employees = Employee::with('department')->paginate(10);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::select('id', 'name')->get();

        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info($request->all());
        // dd($request->all());

        $request->validate([
            'first_name' => 'required|string|max:255|min:3',
            'middle_name' => 'nullable|string|max:255|min:1',
            'last_name' => 'required|string|max:255|min:3',
            'ssn' => 'required|string|size:8|unique:employees,ssn',
            'bdate' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:M,F',
            'salary' => 'nullable|numeric|decimal:0,2|min:0|max:999999.99',
            'department_id' => 'required|exists:departments,id',
        ]);

        $input = $request->all();
        /*Employee::create($input);

        return redirect( route('employees.index') );*/
        try {
            // throw new \Exception("Errore simulato");
            Employee::create($input);

            return redirect(route('employees.index'));

        } catch (\Exception $e) {

            return back()->with('error', 'Errore durante il salvataggio dei dati.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::select('id', 'name')->get();
        
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required|string|max:255|min:3',
            'middle_name' => 'nullable|string|max:255|min:1',
            'last_name' => 'required|string|max:255|min:3',
            'ssn' => 'required|string|size:8|unique:employees,ssn,'.$employee->id,
            'bdate' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:M,F',
            'salary' => 'nullable|numeric|decimal:0,2|min:0|max:999999.99',
            'department_id' => 'required|exists:departments,id',
        ]);

        $input = $request->all();
        $employee->update($input);

        return redirect(route('employees.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Employee eliminato con successo.']);
        }

        return redirect(route('employees.index'));
    }
}
