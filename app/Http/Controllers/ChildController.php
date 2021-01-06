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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'birth_date' => 'required',
            'dad_or_mom_id' => 'required',
            'gender' => ['required', Rule::in(['Niño', 'Niña']),]
        ]);
        
        $birthDate = Carbon::parse($request->birth_date)->format('Y-m-d');
        Child::create([
            'name' => $request->name,
            'birth_date' =>$birthDate,
            'dad_or_mom_id'  => $request->dad_or_mom_id,
            'gender'     => $request->gender,
            'plan_id'    => $request->plan_id
        ]);
        
        return redirect()->route('children.index')->with('successMessage', 'Paciente actualizado sactisfacoriamente');
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
            'plan_id'    => $request->plan_id
        ]);
            
        return redirect()->route('children.index')->with('successMessage', 'Paciente creado sactisfacoriamente');
    }

    public function destroy(Child $child)
    {
        $child->delete();
        return redirect()->route('children.index')->with('successMessage', 'Paciente eliminado sactisfacoriamente');
    }
    
}
