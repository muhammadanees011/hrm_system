<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalTracking extends Model
{
    protected $fillable = [
        'branch',
        'goal_type',
        'start_date',
        'end_date',
        'subject',
        'target_achievement',
        'description',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function performanceCycle()
    {
        return $this->hasOne('App\Models\PerformanceCycle', 'id', 'performancecycle_id');
    }

    public static $status = [
        'Not Started',
        'In Progress',
        'Completed',
    ];
}
