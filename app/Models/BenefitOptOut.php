<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenefitOptOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'reasons',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function benefitscheme()
    {
        return $this->belongsTo(BenefitScheme::class, 'benefit_scheme_id');
    }


}
