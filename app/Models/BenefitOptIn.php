<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BenefitOptIn extends Model
{
    use HasFactory;

  protected  $fillable = [
        'employee_id',
        'benefit_scheme_id',
        'date',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


    public function benefitScheme()
    {
        return $this->belongsTo(BenefitsScheme::class, 'benefit_scheme_id');
    }


}
