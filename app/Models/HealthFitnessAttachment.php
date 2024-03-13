<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthFitnessAttachment extends Model
{
    use HasFactory;

    protected $table="health_and_fitness_attachments";

    protected $fillable = [
        'healthassessment_id',
        'selfcertification_id',
        'gpnotes_id',
        'user_id',
        'files',
    ];
}
