<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;

class EmployeeProjectController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        $projects = Project::all();

        $employee_project = Project::with('employees')->paginate(5);

        return view('employee-project.index', compact('employee_project', 'employees', 'projects'));
    }
    
    public function create(Request $request)
    {
        $employees = Employee::all();
        $projects = Project::all();

        $employee = $request->employee_id 
            ? Employee::find($request->employee_id) 
            : null;

        $project = $request->project_id 
            ? Project::find($request->project_id) 
            : null;

        return view('employee-project.create', compact('employees', 'projects', 'employee', 'project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // TASK 12 — Definisci le regole di validazione per i tre campi:
        ]);

        $employee = Employee::find($request->employee_id);
        $project  = Project::find($request->project_id);

        $employee->projects()->attach($request->project_id, [
            'hours' => $request->hours,
        ]);

        if ($request->ajax()) {
            // TASK 14 — Restituisci una risposta JSON 200 con questa struttura:
        }

        return redirect()->route('employee-project.index')
            ->with('success', 'Relazione creata con successo!');
    }

    // Mostra il form di modifica della relazione
    public function edit(Employee $employee, Project $project)
    {
        $worksOn = $employee->projects()->where('project_id', $project->id)->firstOrFail();
        
        return view('employee-project.edit', compact('employee', 'project', 'worksOn'));
    }

    // Aggiorna le ore di un singolo record pivot
    public function update(Request $request, Employee $employee, Project $project)
    {
        $request->validate([
            'hours' => ['nullable', 'integer', 'min:0'],
        ]);

        $employee->projects()->updateExistingPivot($project->id, [
            'hours' => $request->hours,
        ]);

        return redirect()->route('employee-project.index')->with('success', 'Ore aggiornate con successo!');
    }

    // Elimina la relazione tra employee e project
    public function destroy(Employee $employee, Project $project)
    {
        $employee->projects()->detach($project->id);

        if (request()->ajax()) {
            return response()->json(['message' => 'WorksOn eliminato con successo.']);
        }

        return redirect()->route('employee-project.index')->with('success', 'Progetto rimosso con successo!');
    }
}