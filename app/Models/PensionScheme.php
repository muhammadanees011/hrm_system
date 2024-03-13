<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_name', 'contribution_percentage', 'created_by'
    ];
}
