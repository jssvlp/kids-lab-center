<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VaccineController extends Controller
{
    public function index()
    {
        return Inertia::render('Vaccines/All',[
            'vaccines' => Vaccine::all()
        ]);
    }
    
    public function all()
    {
        $name = request('name');
        if($name)
        {
            return Vaccine::where('name','like','%'.$name.'%')->orderBy('updated_at','desc')->paginate(10);
        }
        return Vaccine::orderBy('updated_at','desc')->paginate(10);
    }

    public function list()
    {
        return Vaccine::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5|unique:vaccines',
            'price' => 'required|numeric',
     
        ]);
        Vaccine::create(['name' => ucwords($request->name), 'price' => $request->price]);

        return redirect()->route('vaccines.index',$request->insurance_id)->with(['toast' => ['message' => 'Vacuna creada correctamente','success' => true]]);
    }

    public function update(Vaccine $vaccine, Request $request)
    {
        $request->validate([
            'name' => 'required|min:5|unique:vaccines',
            'price' => 'required|numeric',
     
        ]);

        $vaccine->update([
            'name' => ucwords($request->name),
            'price' => $request->price
        ]);
        
        return redirect()->route('vaccines.index')->with(['toast' => ['message' => 'Vacuna actualizada correctamente','success' => true]]);
    }

    public function destroy(Vaccine $vaccine)
    {
        $vaccine->delete();
        return redirect()->route('vaccines.index')->with(['toast' => ['message' => 'Vacuna eliminada correctamente','success' => true]]);
    }
}
