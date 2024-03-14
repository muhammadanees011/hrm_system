<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlexiTime extends Model
{
    use HasFactory;
    protected $fillable = ['start_date', 'end_date', 'start_time','remark', 'end_time', 'hours', 'employee_id', 'created_by'];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
