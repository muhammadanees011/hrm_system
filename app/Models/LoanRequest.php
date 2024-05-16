<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    use HasFactory;
    
    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id')->first();
    }

    public function loan_option()
    {
        return $this->hasOne('App\Models\LoanOption', 'id', 'loan_option')->first();
    }
    public static $Loantypes=[
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
}
