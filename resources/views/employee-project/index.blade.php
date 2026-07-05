@extends('layouts.app')

@section('content')
<div class="mt-4 card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mt-4 mb-4">Inserisci un nuovo Works On</h2>
        <button class="btn end-auto" data-bs-toggle="collapse" data-bs-target="#Collapse" aria-expanded="false" aria-controls="Collapse">
            <i class="bi bi-arrows-collapse"></i>
        </button>
    </div>
    <div class="card-body collapse show" id="Collapse">
        <div class="row mt-3">
            <div class="col-md-6">
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <form action="{{ route('employee-project.store') }}" method="POST" id="worksOnForm" class="needs-validation" novalidate>
                    @csrf    
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select name="employee_id" class="form-select" id="employee_id"required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $emp->first_name }} {{ $emp->middle_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                        @if ($errors->has('employee_id'))
                            <label>{{ $errors->first('employee_id') }}</label>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project</label>
                        <select name="project_id" class="form-select" id="project_id"required>
                            <option value="">Select Project</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}">
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                        @if ($errors->has('project_id'))
                            <label>{{ $errors->first('project_id') }}</label>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="hours" class="form-label">Hours</label>
                        <input type="number" class="form-control" id="hours" name="hours" min="0" value="{{ old('hours') }}"/>
                        <div class="invalid-feedback"></div>
                        @if ($errors->has('hours'))
                            <label>{{ $errors->first('hours') }}</label>
                        @endif
                    </div>
                
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-floppy2-fill"></i>
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="mt-5">
    <h1>Employee - Project</h1>
    <div class="row" >
        <div class="col-md-12">
            <a href="{{ route('employee-project.create') }}" class="btn btn-primary float-end mt-3 mb-4">
                <i class="bi bi-plus-lg"></i>&nbsp;Create WorksOn</a>
                
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Employee</th>
                            <th scope="col">Project</th>
                            <th scope="col">Hours</th>
                            <th scope="col">Created</th>
                            <th scope="col">Updated</th>
                            <th scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="worksOnTbody">
                        @foreach($employee_project as $project)
                        @foreach($project->employees as $employee)
                        <tr>
                            <td>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $employee->pivot->hours }}</td>
                            <td>{{ $employee->pivot->created_at->format('d/m/Y') }}</td>
                            <td>{{ $employee->pivot->updated_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('employee-project.edit', [$employee->id, $project->id]) }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-pencil-fill"></i>&nbsp;Edit
                                </a>
                                
                                {{--<form action="{{ route('employee-project.destroy', [$employee->id, $project->id]) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash3-fill"></i>&nbsp;Delete
                                    </button>
                                </form>--}}
                                
                                <button class="btn btn-danger btn-sm btn-delete" 
                                data-url="{{ route('employee-project.destroy', [$employee->id, $project->id]) }}">
                                <i class="bi bi-trash3-fill"></i>&nbsp;Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            {{-- Paginazione --}}
            <div class="d-flex justify-content-end mt-5 g-3">
                {{ $employee_project->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/validation.js','resources/js/delete_alert.js', 'resources/js/ajax/delete_alert.js', 'resources/js/ajax/create_works_on.js'])
@endpush
