<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEventRequest extends Model
{
    use HasFactory;

    public function employees()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function trainingevent()
    {
        return $this->hasOne('App\Models\TrainingEvent', 'id', 'training_event_id');
    }
}
