<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DadOrMom extends Model
{
    use HasFactory;
    protected $fillable = ['name','address', 'phone','kinship'];

    protected $appends = ['allowedDelete'];

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Child::class);
    }

    public function getAllowedDeleteAttribute(): bool
    {
        $anyInvoice = DB::table('invoices')
                        ->join('visits','invoices.visit_id','visits.id')
                        ->join('children','visits.child_id','children.id')
                        ->join('dad_or_moms','children.dad_or_mom_id','dad_or_moms.id')
                        ->where('dad_or_moms.id','=',$this->id)
                        ->count();
        return (int)$anyInvoice === 0;

    }
}
