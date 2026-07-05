@extends('layouts.app')

@section('content')
<div class="row g-3 mt-2">
    
    <!-- Chart dipendenti per department -->
    <div class="col-md-6">
        <div class="card bg-white shadow-sm rounded-lg">
            <canvas id="barChartDE" class="p-4" height="400"></canvas>
        </div>
    </div>

    <!-- Chart stipendi Min/Avg/Max -->
    <div class="col-md-6">
        <div class="card bg-white shadow-sm rounded-lg">
            <canvas id="lineChartDE" class="p-4" height="400"></canvas>
        </div>
    </div>

    <!-- Chart genere -->
    <div class="col-md-6">
        <div class="card bg-white shadow-sm rounded-lg">
            <canvas id="doughnutChartDE" class="p-4"></canvas>
        </div>
    </div>

    <!-- Chart dipendenti per progetti -->
    <div class="col-md-6">
        <div class="card bg-white shadow-sm rounded-lg">
            <canvas id="pieChartPE" class="p-4" height="400"></canvas>
        </div>
    </div>

    <!-- Chart progetti per dipartimento -->
    <div class="col-md-6">
        <div class="card bg-white shadow-sm rounded-lg">
            <canvas id="barChartPD" class="p-4" height="400"></canvas>
        </div>
    </div>

    <!-- Chart progetti per ore --> 
    <div class="col-md-6">
        <div class="card bg-white shadow-sm rounded-lg">
            <canvas id="lineChartPH" class="p-4" height="400"></canvas>
        </div>
    </div>

    
</div>
@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        window.chartDepEmp = @json($chartDepEmp);
        window.chartGrowth = @json($chartGrowth);
        window.chartGender = @json($chartGender);

        window.chartProjDep = @json($chartProjDep);
        window.chartProjEmp = @json($chartProjEmp);
        window.chartProjHours = @json($chartProjHours);
        
    </script>

    @vite(['resources/js/chart/department_employee.js', 'resources/js/chart/project_department.js', 'resources/js/chart/project_employee.js'])

@endpush