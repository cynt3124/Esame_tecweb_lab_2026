<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'site_name',
        'department_id',
    ];

    // Relazione "Ha"
    public function department()
    {
        // TASK 1 — Definisci la relazione: un Project appartiene a (belongsTo) un Department.

         return $this->belongsTo(Department::class);
    }

    public function employees()
    {
        // TASK 2a — Definisci la relazione many-to-many tra Project ed Employee.

            return $this->belongsToMany(Employee::class)
                ->withPivot('hours')
                ->withTimestamps();
    }

}
