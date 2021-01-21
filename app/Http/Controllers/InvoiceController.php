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

        $invoiceNumber = 'KLC'.$invoice->id;
        $invoice->update(['invoince_number' => $invoiceNumber]);

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
        return redirect()->route('invoices.detail',$invoice->id)->with('successMessage', 'Factura creada sactisfactoriamente');
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
            'transaction_number' => $request->transaction_number,
            'payment_status' => 'Pago'
        ]);

        return response()->json(['success' => true, 'message' => 'Pago efectuado correctamente']);
        return redirect()->route('invoices.index',$invoice->id)->with('successMessage', 'Pago realizado correctamente');

    }
}
