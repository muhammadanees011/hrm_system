<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTemplateDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_template_id',
        'title',
        'description',
        'requirement',
        'branch',
        'deparment',
        'contract_type',
        'category',
        'skill',
        'position',
        'start_date',
        'end_date',
        'status',
        'applicant',
        'visibility',
        'code',
        'custom_question',
        'question_template_id',
        'created_by',
    ];

    // Define the relationship with job_templates
    public function jobTemplate()
    {
        return $this->belongsTo(JobTemplate::class);
    }

    public function attachments()
    {
        return $this->hasMany(JobTemplateDetailAttachment::class, 'job_template_code', 'code');
    }

    public static $status = [
        'active' => 'Active',
        'in_active' => 'In Active',
    ];

    public function branches()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch');
    }

    public function departments()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department');
    }

    public function categories()
    {
        return $this->hasOne('App\Models\JobCategory', 'id', 'category');
    }

    public function questions()
    {
        $ids = explode(',', $this->custom_question);

        return CustomQuestion::whereIn('id', $ids)->get();
    }

    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
    public function jobAttachments()
    {
        return $this->hasMany(JobTemplateDetailAttachment::class, 'job_template_code', 'code');
    }
}
