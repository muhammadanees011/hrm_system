<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionOptIn extends Model
{
    use HasFactory;


    protected $fillable = [
        'employee_id',
        'pension_scheme_id',
        'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function pensionScheme()
    {
        return $this->belongsTo(PensionScheme::class, 'pension_scheme_id');
    }
}
