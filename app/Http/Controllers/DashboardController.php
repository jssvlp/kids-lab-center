<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\DadOrMom;
use App\Models\Insurance;
use App\Models\Visit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $dadOrMomsCount = DadOrMom::count();
        $childrenCount  = Child::count();
        $insurancesCount = Insurance::count();
        $visits = Visit::count();

        return Inertia::render('Dashboard',[
             'statistics' => [
                'parents' => $dadOrMomsCount,
                'children' => $childrenCount,
                'insurances' => $insurancesCount,
                'visits'    => $visits
             ]
        ]);
    }
}
