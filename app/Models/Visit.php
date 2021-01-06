<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['visit_date','child_id'];
    protected $appends = ['invoiced'];
    public function vaccines()
    {
        return $this->belongsToMany(Vaccine::class,'visit_vaccines',
                    'visit_id','vaccine_id')->withTimestamps();
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getInvoicedAttribute()
    {
        return $this->invoice() == null;
    }
    
}
