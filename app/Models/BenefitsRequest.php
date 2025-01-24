<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenefitsRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'benefit_id',
        'status',
        'reason',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function benefit()
    {
        return $this->belongsTo(BenefitsScheme::class, 'benefit_id');
    }
}
