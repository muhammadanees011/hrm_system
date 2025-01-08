<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'title',
        'body',
        'status',
        'read',
        'type',
        'data',
    ];

    public function sender()
    {
        return $this->hasOne('App\Models\User', 'id', 'sender_id')->select(['id', 'name']);
    }

    public function receiver()
    {
        return $this->hasOne('App\Models\User', 'id', 'receiver_id')->select(['id', 'name']);
    }
}
