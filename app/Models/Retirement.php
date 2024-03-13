<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'notice_date',
        'retirement_date',
        'retirement_type',
        'exit_stage',
        'description',
        'created_by',
    ];

    public function retirementType()
    {
        return $this->hasOne('App\Models\RetirementType', 'id', 'retirement_type');
    }

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function exitProcedure()
    {
        return $this->hasOne('App\Models\ExitProcedure', 'id', 'exitprocedure_id');
    }
}
