<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlexiTime extends Model
{
    use HasFactory;
    protected $fillable = ['start_date', 'end_date', 'start_time','remark', 'end_time', 'hours', 'employee_id', 'created_by','status','updated_user','updated_user_comment'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function updatedUser()
    {
        return $this->belongsTo(User::class, 'updated_user', 'id');
    }
}
