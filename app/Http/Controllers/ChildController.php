<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ChildController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Children/All',[
            'children' => Child::with(['dadOrMom','plan'])->get()
        ]);
    }
    
    public function all()
    {
        $name = request('name');
        if($name)
        {
            return Child::with(['dadOrMom','plan'])->where('name','like','%'.$name.'%')->orderBy('updated_at','desc')->paginate(10);
        }
        return Child::with(['dadOrMom','plan'])->orderBy('updated_at','desc')->paginate(10);
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
            'name' => $request->name,
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
            'name' => $request->name,
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
    
}
