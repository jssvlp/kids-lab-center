<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DadOrMom extends Model
{
    use HasFactory;
    protected $fillable = ['name','address', 'phone','kinship'];

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Child::class);
    }
}
