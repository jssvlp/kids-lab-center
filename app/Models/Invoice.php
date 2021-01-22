<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [ 'invoice_number','invoice_date',
                            'discount','visit_id','payment_method',
                            'authorization_number','payment_status'
                        ];



    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function vaccines()
    {
        return $this->belongsToMany(Vaccine::class,'invoice_details',
                    'invoice_id','vaccine_id')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
