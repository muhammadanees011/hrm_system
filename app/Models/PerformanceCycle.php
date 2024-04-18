<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;

class PerformanceCycle extends Model
{
    use HasFactory;

    protected $casts = [
        'participants' => 'json',
    ];

    public function roles()
    {
        return $this->hasMany(Role::class, 'id', 'participants');
    }

    public function getParticipantRolesAttribute()
    {
        $participantIds = $this->participants;
        if(is_array($participantIds))
        {
            return Role::whereIn('id', $participantIds)->get();
        }else{
            return Role::where('id', $participantIds[2])->get(); 
        }
    }

    public function getSelectedParticipants()
    {
        $participantIds = $this->participants;
        if(is_array($participantIds))
        {
            return Role::whereIn('id', $participantIds)->get()->pluck('name', 'id');
        }else{
            return Role::where('id', $participantIds[2])->get()->pluck('name', 'id'); 
        }
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\EmployeeReview', 'performancecycle_id', 'id');
    }
}
