<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InsuranceController extends Controller
{
    public function index()
    {
        return Inertia::render('Insurances/All',[
            'insurances' => Insurance::with(['plans'])->get()
        ]);
    }

    public function all()
    {
        $name = request('name');
        if($name){
            return  Insurance::with(['plans'])->where('name','like','%'.$name.'%')->orderBy('updated_at','desc')->get();
        }
        return  Insurance::with(['plans'])->orderBy('updated_at','desc')->get();
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'phone' => 'required',
        ]);
        Insurance::create($request->all());

        return redirect()->route('insurances.index')->with(['toast' => ['message' => 'Aseguradora creada correctamente','success' => true]]);
    }

    public function update(Insurance $insurance, Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'phone' => 'required',
        ]);
        $insurance->update($request->all());

        return redirect()->route('insurances.index')->with(['toast' => ['message' => 'Aseguradora actualizada correctamente','success' => true]]);
    }

    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        return redirect()->route('insurances.index')->with(['toast' => ['message' => 'Aseguradora eliminada correctamente','success' => true]]);
    }

    
}
