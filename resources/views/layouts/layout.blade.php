<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    
    <!--Token-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!--Bootstrap 5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <!--Page Loader-->
    <link rel="stylesheet" href="{{ asset('css/page_loader.css') }}">

    @yield('link')

</head>

<body>
    
    <div id="page-loader" class="position-fixed top-0 start-0 w-100 vh-100 d-flex justify-content-center align-items-center bg-white">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Please wait...</p>
        </div>
    </div>
    
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse nav-underline" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"  href="{{ route('employees.index') }}">Employees</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}"  href="{{ route('departments.index') }}">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}"  href="{{ route('projects.index') }}">Projects</a>
                        </li>
                    </ul>
                </div>
            </div>
                
                
            
            
        </div>
    </nav>
    
    @yield('content')
    
</div>

<!--Bootstrap 5-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<!--Sweet Alert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@yield('script')

</body>
</html>

<script>
    window.addEventListener("load", function () {
        const loader = document.getElementById("page-loader");
        loader.classList.add("hidden");
        
        setTimeout(() => loader.remove(), 500);
    });
</script>