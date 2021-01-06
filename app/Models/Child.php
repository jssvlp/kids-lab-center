<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DadOrMom;
use App\Models\Plan;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender','birth_date', 'dad_or_mom_id','plan_id'];
    protected $appends = ['age'];

    public function dadOrMom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DadOrMom::class);
    }

    public function plan(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function getAgeAttribute()
    {
        return '';
    }

}
