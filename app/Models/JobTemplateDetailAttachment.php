<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTemplateDetailAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_template_code',
        'files',
        'created_by',
    ];

    public function jobTemplateDetail()
    {
        return $this->belongsTo(JobTemplateDetail::class, 'job_template_code', 'code');
    }
}
