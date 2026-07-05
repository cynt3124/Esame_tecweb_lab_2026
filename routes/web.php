<?php

use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes([
    'reset'    => false,
    'confirm'  => false,
    'verify'   => false
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->except('show');

    Route::resource('departments', \App\Http\Controllers\DepartmentController::class)->except('show');

    Route::resource('projects', \App\Http\Controllers\ProjectController::class)->except('show');

    Route::resource('employee-project', \App\Http\Controllers\EmployeeProjectController::class)->except('show', 'edit', 'update', 'destroy');
    Route::prefix('employees/{employee}')->group(function () {
        Route::get('projects/{project}/edit', [\App\Http\Controllers\EmployeeProjectController::class, 'edit'])->name('employee-project.edit');
        Route::put('projects/{project}', [\App\Http\Controllers\EmployeeProjectController::class, 'update'])->name('employee-project.update');
        Route::delete('projects/{project}', [\App\Http\Controllers\EmployeeProjectController::class, 'destroy'])->name('employee-project.destroy');
    });
});
