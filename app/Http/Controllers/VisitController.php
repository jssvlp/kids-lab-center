<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VisitController extends Controller
{
    public function index()
    {
        return Inertia::render('Visits/All',[
            'visits' => Visit::with(['vaccines','child'])->get()
        ]);
    }

    public function show($id)
    {
        return Inertia::render('Visits/Detaill',[
            'visit' => Visit::with(['vaccines','child','child.dadOrMom'])->find($id)
        ]);
    }
    public function vaccines(Visit $visit)
    {
        return $visit->vaccines;
    }
    
    public function store(Request $request)
    {
        $today = Carbon::now();
        $created = Visit::create(['visit_date' => $today, 'child_id' => $request->child_id]);

        return redirect()->route('visits.newOrEdit',$created->id);

    }

    public function addVaccine(Visit $visit, Vaccine $vaccine)
    {
        
        try {
            $visit->vaccines()->attach($vaccine);  
        } catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return response()->json(['status' =>'failure','message' => 'Vacuna ya registrada']);
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function removeVaccine(Visit $visit, $vaccine)
    {
        $visit->vaccines()->wherePivot('vaccine_id', '=', $vaccine)->detach();
        return response()->json(['status' => 'success']);
    }

    public function newOrEdit($id = null)
    {
        $visit =  new Visit();
        $title = 'Nueva visita';

        if($id != null || $id != '_'){
            $visit = Visit::with(['vaccines','child','child.dadOrMom'])->find($id);
            $title = 'Editar visita';
        }

        return Inertia::render('Visits/EditOrNew',[
            'visit' => $visit,
            'title' => $title
        ]);
    }

    public function destroy($id)
    {
        $visit = Visit::find($id);
        $visit->delete();

        return redirect()->route('visits.index')->with('successMessage', 'Visita eliminada sactisfactoriamente');
    }
}
