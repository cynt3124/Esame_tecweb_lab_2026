@extends('layouts.app')

@section('content')

    <h1 class="mt-4 mb-4">Modifica l'Department {{ $department->id}}</h1>

    <div class="row mt-3" >
        <div class="col-md-6">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('departments.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Polo Scientifico Tecnologico" value="{{ $department->name }}" required minlength="3" maxlength="255">
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('name'))
                        <label>{{ $errors->first('name') }}</label>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $department->start_date?->format('Y-m-d') }}">
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('start_date'))
                        <label>{{ $errors->first('start_date') }}</label>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="director_id" class="form-label">Director</label>
                    <select name="director_id" class="form-select" id="director_id">
                        <option value="">Select Director</option>

                        @foreach($department->employees as $employee)
                            <option value="{{ $employee->id }}" @selected($department->director_id == $employee->id)>
                                {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('director_id'))
                        <label>{{ $errors->first('director_id') }}</label>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat"></i>
                    Aggiorna
                </button>

                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
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
