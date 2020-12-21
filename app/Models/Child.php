<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DadOrMom;
use App\Models\Plan;

class Child extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender','birth_date', 'parent_id','plan_id'];

    public function dadOrMom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DadOrMom::class);
    }

    public function plan(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Plan::class);
    }
}
