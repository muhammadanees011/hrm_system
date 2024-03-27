<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'branch',
        'department',
        'header_option',
        'header_title',
        'video_url',
        'video_file_path',
        'image_file_path',
        'header_description',
        'attachments_status',
        'created_by',
    ];

    // Define relationships if any
    public function questions()
    {
        return $this->hasMany(EmployeeOnboardingQuestion::class);
    }

    public function files()
    {
        return $this->hasMany(EmployeeOnboardingFile::class);
    }

    public function branches()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch');
    }

    public function departments()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department');
    }
}
