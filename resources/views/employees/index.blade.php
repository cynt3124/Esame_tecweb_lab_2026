@extends('layouts.app')

@section('content') 

<h1 class="mt-4 mb-4">Lista Employee</h1>

<div class="row" >
    <div class="col-md-12">
        <a href="{{ route('employees.create') }}" class="btn btn-primary float-end mt-3 mb-4">
            <i class="bi bi-plus-lg"></i>&nbsp;Create Employee</a>

        <table class="table table-striped table-hover align-middle text-center">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First name</th>
                    <th scope="col">Middle name (Init.)</th>
                    <th scope="col">Last name</th>
                    <th scope="col">SSN</th>
                    <th scope="col">Birth Date</th>
                    <th scope="col">Address</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Salary</th>
                    <th scope="col">Department</th>
                    {{--<th scope="col">Created</th>
                    <th scope="col">Updated</th>--}}
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>                                
                    <td>{{ $employee->first_name }}</td>
                    <td>{{ $employee->middle_name }}</td>
                    <td>{{ $employee->last_name }}</td>
                    <td>{{ $employee->ssn }}</td>
                    <td>{{ $employee->bdate?->format('d/m/Y') }}</td>
                    <td>{{ $employee->address }}</td>
                    <!--<td>{{ $employee->gender }}</td>-->
                    <td>
                        @if ($employee->gender == 'M')
                            <i class="bi bi-gender-male" style="color: steelblue;"></i>
                            {{ $employee->gender }}
                        @elseif ($employee->gender == 'F')
                            <i class="bi bi-gender-female" style="color: deeppink;"></i>
                            {{ $employee->gender }}
                        @else                         
                            {{ $employee->gender }}
                        @endif
                    </td>
                    <td>{{ $employee->salary }}</td>
                    <td>{{ $employee->department?->name }}</td>
                    {{--<td>{{ $employee->created_at->format('d/m/Y') }}</td>
                    <td>{{ $employee->updated_at->format('d/m/Y') }}</td>--}}
                    <td>
                        <div class="d-grid gap-3 d-md-block mb-1">
                        <a href=" {{ route('employees.edit', $employee->id) }} " class="btn btn-secondary btn-sm">
                            <i class="bi bi-pencil-fill"></i>&nbsp;Edit</a>
                        
                        {{--<form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash3-fill"></i>&nbsp;Delete
                            </button>
                        </form>--}}
                        <button class="btn btn-danger btn-sm btn-delete"
                                data-url="{{ route('employees.destroy', $employee->id) }}">
                            <i class="bi bi-trash3-fill"></i>&nbsp;Delete
                        </button>
                        </div>
                        <div class="d-grid gap-3 d-md-block mb-1">
                        <a href="{{ route('employee-project.create', ['employee_id' => $employee->id]) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-folder-plus"></i>&nbsp;Add Project</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Paginazione --}}
        <div class="d-flex justify-content-end mt-3 g-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/delete_alert.js', 'resources/js/ajax_delete_alert.js'])
@endpush
    
