<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Department;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $departments = Department::select('id', 'name')->get();
        $projects    = Project::with('department')->paginate(10);

        return view('projects.index', compact('projects', 'departments'));
    }

    public function create()
    {
        $departments = Department::select('id', 'name')->get();

        return view('projects.create', compact('departments'));
    }

    public function store(Request $request)
    {
        // TASK 4 — Validazione
        $request->validate([
            'name'          => 'required|string|min:3|max:255',
            'site_name'     => 'nullable|string|min:2|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $input = $request->all();

        // TASK 13 — Risposta JSON se la richiesta è AJAX
        if ($request->ajax()) {
            $project = Project::create($input);
            $project->load('department');

            return response()->json([
                'success' => true,
                'message' => 'Project creato con successo!',
                'data'    => [
                    'id'         => $project->id,
                    'site_name'  => $project->site_name,
                    'department' => $project->department->name,
                    'created_at' => now()->format('d/m/Y'),
                    'updated_at' => now()->format('d/m/Y'),
                ],
            ], 200);
        }

        try {
            // TASK 5 — Creazione del progetto
            Project::create($input);

            return redirect(route('projects.index'));

        } catch (\Exception $e) {

            return back()->with('error', 'Errore durante il salvataggio dei dati.');
        }
    }

    public function show(Project $project)
    {
        //
    }

    public function edit(Project $project)
    {
        $departments = Department::select('id', 'name')->get();

        return view('projects.edit', compact('departments', 'project'));
    }

    public function update(Request $request, Project $project)
    {
        // TASK 6 — Validazione
        $request->validate([
            'name'          => 'required|string|min:3|max:255',
            'site_name'     => 'nullable|string|min:2|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $input = $request->all();

        try {
            // TASK 6 — Aggiornamento
            $project->update($input);

            return redirect(route('projects.index'));

        } catch (\Exception $e) {

            return back()->with('error', 'Errore durante il salvataggio dei dati.');
        }
    }

    public function destroy(Project $project)
    {
        // TASK 7 — Eliminazione
        $project->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Project eliminato con successo.']);
        }

        return redirect(route('projects.index'));
    }
}