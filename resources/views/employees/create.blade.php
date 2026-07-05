@extends('layouts.app')

@section('content') 

<h1 class="mt-4 mb-4">Inserisci un nuovo Employee</h1>

<div class="row mt-3" >
    <div class="col-md-6">
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <form action="{{ route('employees.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf    
            <div class="row g-3">
                <div class="col">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="John" value="{{ old('first_name') }}" required minlength="3" maxlength="255"/>
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('first_name'))
                    <label>{{ $errors->first('first_name') }}</label>
                    @endif
                </div>
                <div class="col">
                    <label for="middle_name" class="form-label">Middle Name (Init.)</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="F" value="{{ old('middle_name') }}" minlength="1" maxlength="255"/>
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('middle_name'))
                    <label>{{ $errors->first('middle_name') }}</label>
                    @endif
                </div>
                <div class="col">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe" value="{{ old('last_name') }}" required minlength="3" maxlength="255"/>
                    <div class="invalid-feedback"></div>
                    @if ($errors->has('last_name'))
                    <label>{{ $errors->first('last_name') }}</label>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label for="ssn" class="form-label">SSN</label>
                <input type="text" class="form-control" id="ssn" name="ssn" placeholder="12345678" value="{{ old('ssn') }}" required pattern="\d{8}" maxlength="8" />
                <div class="invalid-feedback"></div>
                @if ($errors->has('ssn'))
                <div class="alert alert-danger">{{ $errors->first('ssn') }}</div>
                @endif
            </div>
            <div class="mb-3">
                <label for="bdate" class="form-label">Birth Date</label>
                <input type="date" class="form-control" id="bdate" name="bdate"/>
                <div class="invalid-feedback"></div>
                @if ($errors->has('bdate'))
                <label>{{ $errors->first('bdate') }}</label>
                @endif
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Indirizzo</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" maxlength="255"/>
                <div class="invalid-feedback"></div>
                @if ($errors->has('address'))
                <label>{{ $errors->first('address') }}</label>
                @endif
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" class="form-select" id="gender">
                    <option value="">None</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>                        
                </select>
                <div class="invalid-feedback"></div>
                @if ($errors->has('gender'))
                <label>{{ $errors->first('gender') }}</label>
                @endif
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" step="0.01" min="0" max="999999.99" class="form-control" id="salary" name="salary" value="{{ old('salary') }}"/>
                <div class="invalid-feedback"></div>
                @if ($errors->has('salary'))
                <label>{{ $errors->first('salary') }}</label>
                @endif
            </div>
            
            <hr class="border border-2 opacity-100">
            
            <div class="mt-4 mb-3">
                <label for="department_id"class="form-label">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
                @if ($errors->has('department_id'))
                <label>{{ $errors->first('department_id') }}</label>
                @endif
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-floppy2-fill"></i>
                Save
            </button>
            
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
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
