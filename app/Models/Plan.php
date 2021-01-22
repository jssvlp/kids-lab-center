<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name','insurance_id'];

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function children()
    {
        return $this->hasMany(Child::class);
    }
}
