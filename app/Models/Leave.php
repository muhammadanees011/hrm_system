<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
        'Leave_type_id',
        'applied_on',
        'start_date',
        'end_date',
        'total_leave_days',
        'leave_reason',
        'remark',
        'status',
        'duration_type',
        'duration_hours',
        'start_time',
        'end_time',
        'created_by',
    ];

    public function leaveType()
    {
        return $this->hasOne('App\Models\LeaveType', 'id', 'leave_type_id');
    }

    public function employees()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
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
