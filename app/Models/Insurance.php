<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone'];

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
