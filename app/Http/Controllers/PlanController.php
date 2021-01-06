<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;
use Inertia\Inertia;
Use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index(Insurance $insurance)
    {
        return Inertia::render('Insurances/Plans',[
            'plans' => $insurance->plans,
            'insurance' => $insurance
        ]);
    }

    public function list()
    {
        return DB::table('plans')
                ->join('insurances','insurances.id','plans.insurance_id')
                ->select(['plans.id','plans.name','insurances.name as ars'])
                ->get();
    }

    public function store(Request $request)
    {
        Plan::create(['name' => $request->name, 'insurance_id' => $request->insurance_id]);

        return redirect()->route('plans',$request->insurance_id)->with('successMessage', 'User was successfully added!');
        //return response()->json(['status' => 'success', 'message' => 'Plan agregado correctamente']);
    }

    public function update(Plan $plan, Request $request)
    {
        $plan->update(
            $request->all()
        );
        return redirect()->route('plans',$plan->insurance_id)->with('successMessage', 'User was successfully added!');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('plans',$plan->insurance_id)->with('successMessage', 'User was successfully added!');
    }
}
