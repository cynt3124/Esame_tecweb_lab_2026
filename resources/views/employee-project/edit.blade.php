@extends('layouts.app')
@section('content') 
<h1 class="mt-4 mb-4">Modifica {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} - {{ $project->name }}</h1>

<div class="row mt-3">
    <div class="col-md-6">
        
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <form action="{{ route('employee-project.update', [$employee->id, $project->id]) }}" 
            method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            {{-- EMPLOYEE - sempre disabilitata in edit --}}
            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee</label>
                <select name="employee_id" class="form-select" id="employee_id" @disabled(true)>
                    <option value="{{ $employee->id }}">
                        {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}
                    </option>
                </select>
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            </div>
            
            {{-- PROJECT - sempre disabilitata in edit --}}
            <div class="mb-3">
                <label for="project_id" class="form-label">Project</label>
                <select name="project_id" class="form-select" id="project_id" @disabled(true)>
                    <option value="{{ $project->id }}">
                        {{ $project->name }}
                    </option>
                </select>
                <input type="hidden" name="project_id" value="{{ $project->id }}">
            </div>
            
            {{-- HOURS - unico campo editabile --}}
            <div class="mb-3">
                <label for="hours" class="form-label">Hours</label>
                <input type="number" class="form-control" id="hours" name="hours" 
                value="{{ old('hours', $worksOn->pivot->hours) }}"/>
                <div class="invalid-feedback"></div>
                @if ($errors->has('hours'))
                <label>{{ $errors->first('hours') }}</label>
                @endif
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-repeat"></i> Aggiorna
            </button>
            <a href="{{ route('employee-project.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-90deg-left"></i> Indietro
            </a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/validation.js'])
@endpush