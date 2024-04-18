<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewHasReviewee extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function reviewers()
    {
        return $this->hasMany('App\Models\ReviewHasReviewr', 'review_id', 'review_id');
    }

    public function review_result()
    {
        return $this->hasMany('App\Models\ReviewHasResult', 'reviewee_id', 'user_id');
    }
}
