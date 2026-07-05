<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'director_id',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    // Relazione "Dirige"
    public function director()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relazione "Lavora"
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // Relazione "Ha"
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
