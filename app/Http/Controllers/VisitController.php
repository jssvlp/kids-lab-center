<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use App\Models\Child;
use App\Models\Invoice;
use App\Models\Visit;
use App\Models\InvoiceDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VisitController extends Controller
{
    public function index()
    {
        return Inertia::render('Visits/All',[
            //'visits' => Visit::with(['vaccines','child'])->get()
        ]);
    }

    public function all()
    {
        $name = request('name');
        if($name)
        {
            return Visit::with(['child','vaccines'])->whereHas('child',function($query) use ($name){
                return $query->where('name', 'like', '%'.$name.'%');
            })->orderBy('updated_at','desc')->paginate(10);
        }
        return Visit::with(['child','vaccines'])->orderBy('updated_at','desc')->paginate(10);
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
        //Validate if the patient has any visit pending of be invoiced
        $visits = Visit::where('child_id','=',$request->child_id)->get();
        $visits = collect($visits);
        $child = Child::find($request->child_id);

        if(count($visits) > 0)
        {
            $childHasVisitInvoicePending = $visits->firstWhere('invoiced', false);

            if($childHasVisitInvoicePending){
                return redirect()->route('visits.newOrEdit',$childHasVisitInvoicePending['id']);
            }
        }
        
        $today = Carbon::now();
        $created = Visit::create(['visit_date' => $today, 'child_id' => $request->child_id,'child_age' => $child->age]);

        return redirect()->route('visits.newOrEdit',$created->id);

    }

    public function update(Visit $visit, Request $request)
    {   
        $date = Carbon::parse($request->visit_date)->format('Y-m-d');
        $visit->update([
            'visit_date' =>$date
        ]);

        return redirect()->route('invoices.edit',$visit->invoice->id)->with(['toast' => ['message' => 'Visita actualizada correctamente','success' => true]]);
    }

    public function addVaccine(Visit $visit, Vaccine $vaccine)
    {
        
        try {
            $visit->vaccines()->attach($vaccine);
            if($visit->invoiced) 
            {
                $invoice = $visit->invoice;

                InvoiceDetail::create([
                        'invoice_id' => $invoice->id, 
                        'vaccine_id' => $vaccine->id,
                        'price' => $vaccine->price
                ]);
            }  
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
        if($visit->invoiced)
        {
            $invoice = $visit->invoice;
            $invoice->vaccines()->wherePivot('vaccine_id', '=', $vaccine)->detach();
        }
        return response()->json(['status' => 'success']);
    }

    public function newOrEdit($id = null)
    {
        $visit =  new Visit();
        $title = 'Nueva visita';

        if($id != null || $id == '_'){
            $visit = Visit::with(['vaccines','child','child.dadOrMom'])->find($id);
            $title = 'Editar visita';
        }

        return Inertia::render('Visits/EditOrNew',[
            'visit' => $visit,
            'title' => $title
        ]);
    }

    public function getVisit(Visit $visit)
    {
        return response()->json(['success' => true, 'visit' => $visit]);
    }

    public function destroy($id)
    {
        $visit = Visit::find($id);
        $visit->delete();

        return redirect()->route('visits.index')->with('successMessage', 'Visita eliminada sactisfactoriamente');
    }
}
