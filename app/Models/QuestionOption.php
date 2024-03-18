<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['question_id', 'option_text', 'branching_logic'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public static function getOptionTextById($optionId)
    {
        $option = static::find($optionId);

        return $option ? $option->option_text : null;
    }
}
