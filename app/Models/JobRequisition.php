<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequisition extends Model
{
    use HasFactory;

    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
