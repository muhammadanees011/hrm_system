<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['question_template_id', 'name', 'type', 'word_count'];

    public function questionTemplate()
    {
        return $this->belongsTo(QuestionTemplate::class);
    }

    public function jobApplicationAnswers()
    {
        return $this->hasMany(JobApplicationAnswer::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('id', 'asc');
    }
}
