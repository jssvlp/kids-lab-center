<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = ['name','price'];

    public function visits()
    {
        return $this->belongsToMany(Visit::class,'visit_vaccines',
                    'vaccine_id','visite_id')->withTimestamps();
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class,'invoice_details',
                    'vaccine_id','invoice_id')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
