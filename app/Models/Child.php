<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DadOrMom;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Child extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender','birth_date', 'dad_or_mom_id','plan_id','health_insurance_id'];
    protected $appends = ['age','visitsCount','lastVisit','allowedDelete'];

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
        return \Carbon\Carbon::parse( $this->birth_date )->diff(\Carbon\Carbon::now())->format('%y años y %m meses');
    }

    public function getVisitsCountAttribute()
    {
        return count($this->visits);
    }

    public function getLastVisitAttribute()
    {

        if(count($this->visits) > 0)
        {
            $visits = $this->visits->sortByDesc('visit_date');

            $today = Carbon::now()->format('Y-m-d');

            if(count($visits) == 1)
            {
                return '';
            }

            $index = count($visits) - 2;
            return $visits[$index];
        }
        return '';
    }

    public function getAllowedDeleteAttribute(): bool
    {
        $anyInvoice = DB::table('invoices')
            ->join('visits','invoices.visit_id','visits.id')
            ->join('children','visits.child_id','children.id')
            ->where('children.id','=',$this->id)
            ->count();
        return (int)$anyInvoice === 0;

    }

}
