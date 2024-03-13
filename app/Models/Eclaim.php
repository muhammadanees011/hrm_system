<?php

namespace App\Models;

use App\Models\Department;
use App\Models\EclaimType;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Eclaim extends Model
{
    use HasFactory;

    public function claimType(){
        return $this->belongsTo(EclaimType::class, 'type_id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    protected static function booted(){
        static::addGlobalScope(function(Builder $builder){
            if(\Auth::user()->type=="manager" && !empty(\Auth::user()->assigned_departments)){
                $assignedDepartments = Department::get()->pluck('id');
                $employees = Employee::whereIn('department_id', $assignedDepartments)->get()->pluck('id');
                $builder->whereIn('employee_id', $employees);
            }
        });
    }
}
