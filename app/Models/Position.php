<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'status',
        'branch',
        'department',
        'job_level',
        'created_by',
    ];

    public function branches()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch');
    }

    public function departments()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department');
    }

    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
