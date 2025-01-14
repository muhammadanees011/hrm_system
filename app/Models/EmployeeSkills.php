<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSkills extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'skill_name',
        'skill_description',
    ];
}
