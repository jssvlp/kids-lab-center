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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5|unique:vaccines',
            'price' => 'required|numeric',
     
        ]);
        Vaccine::create(['name' => $request->name, 'price' => $request->price]);

        return redirect()->route('vaccines.index',$request->insurance_id)->with('successMessage', 'Vacuna was successfully added!');
    }

    public function update(Vaccine $vaccine, Request $request)
    {
        $request->validate([
            'name' => 'required|min:5|unique:vaccines',
            'price' => 'required|numeric',
     
        ]);

        $vaccine->update([
            'name' => $request->name,
            'price' => $request->price
        ]);
        
        return redirect()->route('vaccines.index')->with('successMessage', 'Vacuna actualizada sactisfacoriamente');
    }

    public function destroy(Vaccine $vaccine)
    {
        $vaccine->delete();
        return redirect()->route('vaccines.index')->with('successMessage', 'Vacuna eliminada sactisfacoriamente');
    }
}
