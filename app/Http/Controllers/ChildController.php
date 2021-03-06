<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ChildController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Children/All',[
            //'children' => Child::with(['dadOrMom','plan'])->get()
        ]);
    }

    public function all()
    {
        $name = request('name');
        if($name)
        {
            return Child::with(['dadOrMom','plan','visits','visits'])->where('name','like','%'.$name.'%')->orderBy('updated_at','desc')->paginate(8);
        }
        return Child::with(['dadOrMom','plan','visits'])->orderBy('updated_at','desc')->paginate(8);
    }

    public function list()
    {
        return Child::all();
    }

    public function history($id)
    {
        $child = Child::with('visits.vaccines','dadOrMom','plan.insurance')->find($id);

        return Inertia::render('Children/History',[
            'child' => $child
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'birth_date' => 'required',
            'dad_or_mom_id' => 'required',
            'gender' => ['required', Rule::in(['Niño', 'Niña']),]
        ]);

        $birthDate = Carbon::parse($request->birth_date)->format('Y-m-d');
        $created = Child::create([
            'name' => ucwords(strtolower($request->name)),
            'birth_date' =>$birthDate,
            'dad_or_mom_id'  => $request->dad_or_mom_id,
            'gender'     => $request->gender,
            'plan_id'    => $request->plan_id,
            'health_insurance_id' => $request->health_insurance_id
        ]);

        return redirect()->route('children.index')->with(['toast' => ['message' => 'Paciente creado correctamente','success' => true]]);
    }

    public function update(Child $child, Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'birth_date' => 'required',
            'dad_or_mom_id' => 'required',
            'gender' => ['required', Rule::in(['Niño', 'Niña']),]
        ]);

        $birthDate = Carbon::parse($request->birth_date)->format('Y-m-d');

        $child->update([
            'name' => ucwords(strtolower($request->name)),
            'birth_date' =>$birthDate,
            'parent_id'  => $request->parent_id,
            'gender'     => $request->gender,
            'plan_id'    => $request->plan_id,
            'health_insurance_id' => $request->health_insurance_id
        ]);

        return redirect()->route('children.index')->with(['toast' => ['message' => 'Paciente actualizado correctamente','success' => true]]);
    }

    public function destroy(Child $child)
    {
        $child->delete();
        return redirect()->route('children.index')->with(['toast' => ['message' => 'Paciente eliminado correctamente','success' => true]]);
    }

    public function checkPlanInsuranceNumber(Request $request)
    {
        $plan = Plan::with(['insurance'])->find($request->plan_id);

        $child = Child::where('plan_id','=',$plan->id)->where('health_insurance_id','=',$request->health_insurance_id)->first();

        if(!$child or $child->id == $request->child_id){
            return response()->json(['exists' => false]);
        }

        return response()->json(['exists' => true]);
    }

    private function childExists(Child $child)
    {

    }

}
