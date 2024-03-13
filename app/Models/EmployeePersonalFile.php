<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePersonalFile extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id', 'name', 'file', 'created_by'];

    /**
     * Get the employee that owns the personal file.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
