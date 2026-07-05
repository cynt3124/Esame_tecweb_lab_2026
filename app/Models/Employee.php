<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'ssn',
        'bdate',
        'address',
        'gender',
        'salary',
        'department_id'
    ];

    protected $casts = [
        'bdate' => 'date',
    ];

    // Relazione "Dirige"
    public function manage()
    {
        return $this->hasOne(Department::class);
    }

    // Relazione "Lavora"
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function projects()
    {
        // TASK 2b — Definisci la relazione many-to-many tra Project ed Employee.
            return $this->belongsToMany(Project::class)
                ->withPivot('hours')
                ->withTimestamps();
    }
}
