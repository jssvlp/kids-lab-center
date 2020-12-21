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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'phone' => 'required',
        ]);
        Insurance::create($request->all());

        return redirect()->route('insurances.index')->with('successMessage', 'Aseguradora creada sactisfacoriamente');
    }

    public function update(Insurance $insurance, Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'phone' => 'required',
        ]);
        $insurance->update($request->all());

        return redirect()->route('insurances.index')->with('successMessage', 'Aseguradora actualizada sactisfacoriamente');
    }

    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        return redirect()->route('insurances.index')->with('successMessage', 'Aseguradora eliminada sactisfacoriamente');
    }

    
}
