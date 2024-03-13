<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['job_code', 'files', 'created_by'];
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_code', 'code');
    }
}
