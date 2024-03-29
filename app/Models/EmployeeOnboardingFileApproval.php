<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingFileApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_onboarding_template_id',
        'employee_onboarding_file_id',
        'job_application_id',
        'approve_status',
    ];

    public function onboardingTemplate()
    {
        return $this->belongsTo(EmployeeOnboardingTemplate::class, 'employee_onboarding_template_id');
    }

    public function onboardingFile()
    {
        return $this->belongsTo(EmployeeOnboardingFile::class, 'employee_onboarding_file_id');
    }


    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
}
