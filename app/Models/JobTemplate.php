<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'created_by',
    ];

    public function details()
    {
        return $this->hasMany(JobTemplateDetail::class);
    }
}
