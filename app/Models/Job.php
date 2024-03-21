<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
        'description',
        'requirement',
        'branch',
        'department',
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
        return $this->hasMany(JobAttachment::class, 'job_code', 'code');
    }
}
