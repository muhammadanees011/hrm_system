<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayCarryOver extends Model
{
    use HasFactory;

    protected $table = 'holiday_carryovers';
    protected $fillable = [
        'total_days',
        'status',
        'user_id',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
