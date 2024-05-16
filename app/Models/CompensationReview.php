<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompensationReview extends Model
{
    use HasFactory;

    public function reviewees()
    {
        return $this->hasMany('App\Models\CompensationReviewHasReviewees', 'compensation_review_id', 'id');
    }
}
