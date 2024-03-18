<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'created_by'];

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('id', 'asc');
    }
}
