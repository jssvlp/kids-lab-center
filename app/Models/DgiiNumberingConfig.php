<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DgiiNumberingConfig extends Model
{
    use HasFactory;

    protected $fillable = ['init','end','completed','config_date','active'];

    protected $appends = ['hasInvoices','total','totalUsed'];


    public function getHasInvoicesAttribute(): bool
    {
        return $this->getTotalUsedAttribute() > 0;
    }

    public function getTotalAttribute()
    {
        return $this->end - $this->init;
    }


    public function getTotalUsedAttribute()
    {
        return DgiiSequence::where('dgii_numbering_config_id','=', $this->id)->count();
    }

}
