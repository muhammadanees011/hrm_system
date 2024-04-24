<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseDiscussion extends Model
{
    use HasFactory;
    protected $fillable = [
        'case_code',
        'sender',
        'receiver',
        'text',
        'type',
        'file',
    ];

    public function case()
    {
        return $this->belongsTo(VoiceCase::class, 'case_code', 'uuid');
    }
}
