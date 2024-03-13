<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProbation;
use Illuminate\Http\Request;

class EmployeeProbationController extends Controller
{
    public static function store($employee_Id, $probation_days)
    {
        $probation =  EmployeeProbation::create([
            'employee_id' => $employee_Id,
            'duration' => $probation_days,
            'started_date' => now(),
            'created_by' => \Auth::user()->creatorId(),
        ]);

        if ($probation) {
            return true;
        }
    }
}
