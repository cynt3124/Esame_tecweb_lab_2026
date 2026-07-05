@extends('layouts.app')

@section('content')
<div class="mt-4 card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mt-4 mb-4">Inserisci un nuovo Department</h2>
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
                <form action="{{ route('departments.store') }}" method="POST" id="departmentForm" class="needs-validation" novalidate>
                    @csrf    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Polo Scientifico Tecnologico" value="{{ old('name') }}" required minlength="3" maxlength="255">
                        <div class="invalid-feedback"></div>
                        @if ($errors->has('name'))
                            <label>{{ $errors->first('name') }}</label>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        <div class="invalid-feedback"></div>
                        @if ($errors->has('start_date'))
                            <label>{{ $errors->first('start_date') }}</label>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="director_id" class="form-label">Director</label>
                        <select name="director_id" class="form-select" id="director_id">
                            <option value="">Select Director</option>

                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                        @if ($errors->has('director_id'))
                            <label>{{ $errors->first('director_id') }}</label>
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
    <h1>Lista Department</h1>

    <div class="row" >
        <div class="col-md-12">
            <a href="{{ route('departments.create') }}"class="btn btn-primary float-end mt-3 mb-4">
                <i class="bi bi-plus-lg"></i>&nbsp;Create Department</a>
            
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Director</th>
                        <th scope="col">Created</th>
                        <th scope="col">Updated</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="departmentTbody">
                    @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>                                
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->start_date?->format('d/m/Y') }}</td>
                        <td>{{ $department->director?->first_name }} {{ $department->director?->middle_name }} {{ $department->director?->last_name }}</td>
                        <td>{{ $department->created_at->format('d/m/Y') }}</td>
                        <td>{{ $department->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <a href=" {{ route('departments.edit', $department->id) }} " class="btn btn-secondary btn-sm">
                                <i class="bi bi-pencil-fill"></i>&nbsp;Modifica</a>

                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash3-fill"></i>&nbsp;Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Paginazione --}}
            <div class="d-flex justify-content-end mt-3 g-3">
                {{ $departments->links() }}
            </div>                   
        </div>
    </div>
</div>
@endsection
            
@push('scripts')
    @vite(['resources/js/validation.js','resources/js/delete_alert.js', 'resources/js/ajax/delete_alert.js', 'resources/js/ajax/create_departments.js'])
@endpush