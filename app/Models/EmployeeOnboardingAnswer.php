<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_onboarding_template_id',
        'employee_onboarding_question_id',
        'job_application_id',
        'answer',
    ];

    public function onboardingTemplate()
    {
        return $this->belongsTo(EmployeeOnboardingTemplate::class, 'employee_onboarding_template_id');
    }

    public function onboardingQuestion()
    {
        return $this->belongsTo(EmployeeOnboardingQuestion::class, 'employee_onboarding_question_id');
    }

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
}
