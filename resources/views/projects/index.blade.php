@extends('layouts.app')

@section('content') 
<div class="mt-4 card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mt-4 mb-4">Inserisci un nuovo Project</h2>
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
                <form action="{{ route('projects.store') }}" method="POST" id="projectForm" class="needs-validation" novalidate>
                    @csrf    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Progettone" value="{{ old('name') }}" required minlength="3" maxlength="255"/>
                        <div class="invalid-feedback"></div>
                        @if ($errors->has('name'))
                            <label>{{ $errors->first('name') }}</label>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" placeholder="Ferrara" value="{{ old('site_name') }}" minlength="2" maxlength="255"/>
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
    <h1>Lista Project</h1>

    <div class="row" >
        <div class="col-md-12">
            <a href="{{ route('projects.create') }}" class="btn btn-primary float-end mt-3 mb-4">
                <i class="bi bi-plus-lg"></i>&nbsp;Create Project</a>

            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Site Name</th>
                        <th scope="col">Department</th>
                        <th scope="col">Created</th>
                        <th scope="col">Updated</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="projectTbody">
                    @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>                                
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->site_name }}</td>
                        <td>{{ $project->department?->name }}</td>
                        <td>{{ $project->created_at->format('d/m/Y') }}</td>
                        <td>{{ $project->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-pencil-fill"></i>&nbsp;Edit
                            </a>

                            {{--<form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash3-fill"></i>&nbsp;Delete
                                </button>
                            </form>--}}

                            <button class="btn btn-danger btn-sm btn-delete" data-url="{{ route('projects.destroy', $project->id) }}">
                                <i class="bi bi-trash3-fill"></i>&nbsp;Delete
                            </button>
                            
                            <a href="{{ route('employee-project.create', ['project_id' => $project->id])  }}" class="btn btn-success btn-sm">
                                <i class="bi bi-person-add"></i>&nbsp;Add Employee
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{-- Paginazione --}}
            <div class="d-flex justify-content-end mt-3 g-3">
                {{ $projects->links() }}
            </div> 
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/validation.js','resources/js/delete_alert.js', 'resources/js/ajax/delete_alert.js', 'resources/js/ajax/create_projects.js'])
@endpush
