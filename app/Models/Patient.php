<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'gender','birth_date', 'parent_id','plan_id'];

    public function parentt()
    {
        return $this->belongsTo(App\Model\Parentt::class);
    }

    public function plan()
    {
        return $this->hasOne(App\Models\Plan::class);
    }
}
