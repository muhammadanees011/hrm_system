<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewQuestion extends Model
{
    use HasFactory;

    public function reviewResult()
    {
        return $this->hasMany('App\Models\ReviewHasResult', 'review_id', 'review_id');
    }
}
