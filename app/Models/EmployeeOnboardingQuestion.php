<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_onboarding_template_id',
        'name',
        'type',
        'word_count',
        'options',
    ];

    protected $casts = [
        'options' => 'json',
    ];

    public function template()
    {
        return $this->belongsTo(EmployeeOnboardingTemplate::class);
    }
}
