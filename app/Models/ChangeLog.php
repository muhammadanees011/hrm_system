<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'changed_by',
        'old_values',
        'new_values',
        'status',
        'notification_id',
    ];
}
