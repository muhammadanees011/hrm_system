<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingTemplate extends Model
{
    use HasFactory;

    public function points()
    {
        return $this->hasMany(MeetingTemplatePoint::class, 'meeting_template_id', 'id');
    }
}
