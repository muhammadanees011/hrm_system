<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployementCheck extends Model
{
    use HasFactory;

    protected $fillable=[
        'employementcheck_type',
        'employee_id',
        'files',
    ];

    public function employementcheckType()
    {
        return $this->hasOne('App\Models\EmployementCheckType', 'id', 'employementcheck_type');
    }

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }
}
