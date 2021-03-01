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
        $plans = DB::table('plans')
                ->join('insurances','insurances.id','plans.insurance_id')
                ->select(['plans.id','plans.name','insurances.name as ars'])
                ->get()->toArray();
        $noOne = ['id' => 0, 'name' => 'Sin seguro', 'ars' => 'Ninguna'];
        array_unshift($plans,$noOne);

        return $plans;
    }

    public function store(Request $request)
    {
        Plan::create(['name' => ucwords(strtolower($request->name)), 'insurance_id' => $request->insurance_id]);

        return redirect()->route('plans',$request->insurance_id)->with(['toast' => ['message' => 'Plan creado correctamente','success' => true]]);
        //return response()->json(['status' => 'success', 'message' => 'Plan agregado correctamente']);
    }

    public function update(Plan $plan, Request $request)
    {
        $plan->update(
            ['name' => ucwords($request->name)]
        );
        return redirect()->route('plans',$plan->insurance_id)->with(['toast' => ['message' => 'Plan actulizado correctamente','success' => true]]);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('plans',$plan->insurance_id)->with(['toast' => ['message' => 'Plan eliminado correctamente','success' => true]]);
    }
}
