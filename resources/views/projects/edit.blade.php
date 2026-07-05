@extends('layouts.app')

@section('content') 

    <h1 class="mt-4 mb-4">Modifica il Project {{ $project->id}}</h1>
        
    <div class="row mt-3" >
        <div class="col-md-6">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('projects.update', $project->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')   
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Progettone" value="{{ $project->name }}" minlength="3" maxlength="255"/>
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('name'))
                        <label>{{ $errors->first('name') }}</label>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="site_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name" placeholder="Bho" value="{{ $project->site_name }}"" minlength="2" maxlength="255"/>
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('site_name'))
                        <label>{{ $errors->first('site_name') }}</label>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="department_id"class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected($project->department_id == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('department_id'))
                        <label class="text-danger">{{ $errors->first('department_id') }}</label>
                    @endif
                </div>
                
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-floppy2-fill"></i>
                    Aggiorna
                </button>

                <a href="{{ route('projects.index') }}" class="btn btn-secondary">
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
