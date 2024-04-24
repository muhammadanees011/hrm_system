<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceCase extends Model
{
    use HasFactory;

    protected $table = 'cases';
    protected $fillable = [
        'title',
        'case_category_id',
        'uuid',
        'is_private',
        'status',
        'representative',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the category associated with the voice case.
     */
    public function category()
    {
        return $this->belongsTo(CaseCategory::class, 'case_category_id');
    }

    public function discussions()
    {
        return $this->hasMany(CaseDiscussion::class, 'case_code', 'uuid');
    }
}
