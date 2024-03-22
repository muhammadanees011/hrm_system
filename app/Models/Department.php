<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Department extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function branch(){
        return $this->hasOne('App\Models\Branch','id','branch_id');
    }

    protected static function booted(){
        static::addGlobalScope(function(Builder $builder){
            if(\Auth::user() && \Auth::user()->type=="manager" && !empty(\Auth::user()->assigned_departments)){
                $builder->whereIn('id',\Auth::user()->assigned_departments);
            }
        });
    }
}
