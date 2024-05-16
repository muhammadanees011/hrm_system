<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceRequest extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id')->first();
    }

    public function allowance_option()
    {
        return $this->hasOne('App\Models\AllowanceOption', 'id', 'allowance_option')->first();
    }

    public static $Allowancetype = [
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
}
