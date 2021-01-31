<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Invoices/All',[
            'invoices' => Invoice::with(['visit','visit.child','visit.vaccines'])->get()->toArray()
        ]);
    }

    public function all()
    {
        $name = request('name');
        if($name)
        {
            return Invoice::with(['visit','visit.child','visit.vaccines'])->whereHas('visit',function($query) use ($name){
                return $query->whereHas('child', function($query) use ($name){
                     return $query->where('name', 'like', '%'.$name.'%');
                 });
                 
             })->orderBy('updated_at','desc')->paginate(8);
        }
        return Invoice::with(['visit','visit.child','visit.vaccines'])->orderBy('updated_at','desc')->paginate(8);
    }

    public function print($invoice)
    {
        $_invoice = Invoice::with(['visit','visit.child','visit.child.dadOrMom','visit.child.plan.insurance','vaccines'])->whereId($invoice)->first();
       
        return Inertia::render('Invoices/Print',[
            'invoice' => $_invoice
        ]);
    }

    public function detail($invoice)
    {
        $_invoice = Invoice::with(['visit','visit.child','visit.child.dadOrMom','visit.child.plan.insurance','vaccines'])->whereId($invoice)->first();
       
        if($_invoice['payment_status']=='Pago')
        {
            return Inertia::render('Invoices/Show',[
                'invoice' => $_invoice
            ]);
        }
        return Inertia::render('Invoices/EditOrNew',[
            'invoice' => $_invoice
        ]);
    }

    public function store(Request $request)
    {
        //TODO: validate

        //1. Create the invoice
        $invoice = Invoice::create([
            'invoice_date' => Carbon::now(),
            'visit_id' => $request->visit_id,
        ]);

        $invoice->invoice_number = 'KLC'.$invoice->id;
        $invoice->save();

        //2. Get the vaccines 
        $visit = Visit::find($request->visit_id);

        //3. Create the invoice detail with de vaccines
        foreach ($visit->vaccines as $key => $vaccine) {
           InvoiceDetail::create(
                [
                    'invoice_id' => $invoice->id, 
                    'vaccine_id' => $vaccine->id,
                    'price' => $vaccine->price
                    ]
           );
        }
        return redirect()->route('invoices.detail',$invoice->id)->with(['toast' => ['message' => 'Factura creada correctamente','success' => true]]);
    }


    public function pay(Invoice $invoice, Request $request)
    {
        //1. Update all the vaccines prices
        $_vaccines = $request->vaccines;

        foreach ($_vaccines as $key => $vaccine) {
            $invoice->vaccines()->updateExistingPivot($_vaccines[$key]['id'],['price' =>$_vaccines[$key]['price']],true);
        }

        //2. Set discount, payment method, authorizacion number and payment_status ('Pago')
        $updated = $invoice->update([
            'discount' => $request->discount,
            'payment_method' => $request->payment_method,
            'authorization_number' => $request->authorization,
            'payment_status' => 'Pago'
        ]);
        return response()->json(['success' => true, 'message' => 'Pago efectuado correctamente']);
    }
}
