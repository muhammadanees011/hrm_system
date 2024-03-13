<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GPNote extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\HealthFitnessAttachment', 'gpnotes_id' , 'id');
    }
}
