<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProbation extends Model
{
    use HasFactory;

    protected $table = 'employee_probation'; 

    protected $fillable = [
        'employee_id',
        'duration',
        'started_date',
        'created_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
