<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','created_by','department_id'
    ];

    public function departments()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }
}
