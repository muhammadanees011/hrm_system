<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReview extends Model
{
    use HasFactory;

    public function performanceCycle()
    {
        return $this->hasOne('App\Models\PerformanceCycle', 'id', 'performancecycle_id');
    }

    public function reviewers()
    {
        return $this->hasMany('App\Models\ReviewHasReviewr', 'review_id', 'id');
    }

    public function reviewees()
    {
        return $this->hasMany('App\Models\ReviewHasReviewee', 'review_id', 'id');
    }

}
