<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DadOrMom;
use App\Models\Plan;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'gender','birth_date', 'dad_or_mom_id','plan_id','health_insurance_id'];
    protected $appends = ['age','visitsCount','lastVisit'];

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
            
            /* if($invoice != null && $visit->visit_date != $today)
            {
                return $visit->visit_date;
            } */
            
            /* $visit = Visit::where('visit_date','<=',$today)
                        ->orderBy('visit_date','desc')->first();
            return $visit->visit_date; */
            
        }
        return '';
        
    }

}
