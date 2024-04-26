<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_onboarding_template_id',
        'uuid',
        'file_type',
        'file_path',
    ];

    public function template()
    {
        return $this->belongsTo(EmployeeOnboardingTemplate::class);
    }
}
