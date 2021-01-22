<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\DadOrMom;
use App\Models\Parentt;
use Illuminate\Validation\Rule;

class ParentController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Parents/All',[
           'parents' => DadOrMom::with(['children'])->get()
        ]);
    }

    public function show(DadOrMom $parent)
    {
        return $parent;
    }

    public function all()
    {
        $name = request('name');
        if($name)
        {
            return DadOrMom::with(['children'])->where('name','like','%'.$name.'%')->orderBy('updated_at','desc')->paginate(7);
        }
        return DadOrMom::with(['children'])->orderBy('updated_at','desc')->paginate(7);
    }

    public function list()
    {
        return DadOrMom::select('id','name','kinship')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'phone' => 'required',
            'address' => 'required|min:5',
            'kinship' => ['required', Rule::in(['Padre', 'Madre']),]
        ]);

        DadOrMom::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'kinship' => $request->kinship
        ]);

        return redirect()->route('parents.index')->with('successMessage', $request->kinship.' creado sactisfacoriamente');
    }

    public function update(DadOrMom $parent, Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'phone' => 'required',
            'address' => 'required|min:5',
            'kinship' => ['required', Rule::in(['Padre', 'Madre']),]
        ]);
        
        $parent->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'kinship' => $request->kinship
            ]
        );
        return redirect()->route('parents.index')->with('successMessage', $request->kinship.' creado sactisfacoriamente');
    }

    public function destroy(DadOrMom $parent)
    {
        $parent->delete();
        return redirect()->route('parents.index')->with('successMessage', 'Eliminado sactisfacoriamente');
    }
}
