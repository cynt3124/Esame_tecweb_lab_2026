@extends('layouts.app')

@section('content') 

<h1 class="mt-4 mb-4">Inserisci un nuovo Works On</h1>

<div class="row mt-3" >
    <div class="col-md-6">
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <form action="{{ route('employee-project.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf    
            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee</label>
                <select name="employee_id" 
                class="form-select" 
                id="employee_id"
                required
                @disabled($employee != null)>
                <option value="">Select Employee</option>
                @foreach($employees as $emp)
                <option value="{{ $emp->id }}"
                    {{ $employee && $employee->id == $emp->id ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->middle_name }} {{ $emp->last_name }}
                </option>
                @endforeach
            </select>
            @if($employee)
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            @endif
            <div class="invalid-feedback"></div>
            @if ($errors->has('employee_id'))
            <label>{{ $errors->first('employee_id') }}</label>
            @endif
        </div>
        <div class="mb-3">
            <label for="project_id" class="form-label">Project</label>
            <select name="project_id" 
            class="form-select" 
            id="project_id"
            required
            @disabled($project != null)>
            <option value="">Select Project</option>
            @foreach($projects as $proj)
            <option value="{{ $proj->id }}"
                {{ $project && $project->id == $proj->id ? 'selected' : '' }}>
                {{ $proj->name }}
            </option>
            @endforeach
        </select>
        @if($project)
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        @endif
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
    
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-floppy2-fill"></i>
        Save
    </button>
    
    <a href="{{ route('employee-project.index') }}"  class="btn btn-secondary">
        <i class="bi bi-arrow-90deg-left"></i>
        Indietro
    </a>
</form>
</div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/validation.js'])
@endpush
