<?php

namespace App\Providers;

use App\Models\DgiiNumberingConfig;
use App\Models\DgiiSequence;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Inertia::share('app.name', config('app.name'));

        Inertia::share('errors', function () {
            return session()->get('errors') ? session()->get('errors')->getBag('default')->getMessages() : (object) [];
        });

        Inertia::share('toast', function () {
            return session()->get('toast') ? session()->get('toast') : null;
        });

        Inertia::share('dgii', function () {
            $currentSequence = DgiiNumberingConfig::where('active',true)->first();
            $DGII_LIMIT_INVOICES_ALERT = env('DGII_LIMIT_INVOICES_ALERT');

            if(!$currentSequence)
            {
                return [
                    'used' => null,
                    'total' => null,
                    'showAlert' => false
                ];
            }
            $nextSequence = DgiiNumberingConfig::where('init',$currentSequence->end + 1)->first();
            $showAlert = false;
            if($nextSequence != null)
            {
                $showAlert = false;
            }
            elseif ($currentSequence->total - $currentSequence->totalUsed <= $DGII_LIMIT_INVOICES_ALERT){
                $showAlert = true;
            }

            return  [
                'used' => $currentSequence->totalUsed,
                'total' => $currentSequence->total,
                'showAlert' => $showAlert
            ];
        });
    }

    /**
     * Bootstrap any application services.r
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
