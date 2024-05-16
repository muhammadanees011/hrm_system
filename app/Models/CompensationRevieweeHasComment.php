<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompensationRevieweeHasComment extends Model
{
    use HasFactory;

    public function commenter()
    {
        return $this->hasOne('App\Models\User', 'id', 'commenter_id');
    }
}
