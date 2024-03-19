<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'question_id',
        'answer'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
