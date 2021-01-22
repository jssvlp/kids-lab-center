<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Child;

class Parentt extends Model
{
    use HasFactory;
    protected $fillable = ['name','address', 'phone','kinship'];

    public function children()
    {
        return $this->hasMany(Child::class);
    }
}
