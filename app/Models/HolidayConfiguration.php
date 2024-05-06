<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'annual_entitlement',
        'total_annual_working_days',
        'created_by'
    ];
}
