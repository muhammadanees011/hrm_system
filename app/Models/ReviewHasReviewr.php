<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewHasReviewr extends Model
{
    use HasFactory;
    
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
