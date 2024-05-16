<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompensationReviewHasReviewees extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'reviewee_id');
    }

    public function reviewer()
    {
        return $this->hasMany('App\Models\CompensationRevieweeHasReviewer', 'reviewee_id', 'reviewee_id');
    }
}
