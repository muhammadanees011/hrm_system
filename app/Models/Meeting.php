<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'organizer_id',
        'invitee_id',
        'meeting_template_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'note',
        'created_by',
    ];

    public function organizer()
    {
        return $this->hasOne(User::class,'id','organizer_id');
    }

    public function invitee()
    {
        return $this->hasOne(User::class,'id','invitee_id');
    }

    public function meetingTemplate()
    {
        return $this->hasOne(meetingTemplate::class,'id','meeting_template_id');
    }
}
