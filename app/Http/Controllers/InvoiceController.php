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
            //'invoices' => Invoice::with(['visit','visit.child','vaccines'])->get()->toArray()
        ]);
    }

    public function all()
    {
        $filter = request('filter');
        $orderBy  = request('orderby');
        $descOrAsc = request('descOrAsc');
        $defaultOrderBy = 'created_at';
        $desc = 'desc';

        if($filter)
        {
            return Invoice::with(['visit','visit.child','vaccines','dgiiSequence'])->whereHas('visit',function($query) use ($filter){
                return $query->whereHas('child', function($query) use ($filter){
                     return $query->where('name', 'like', '%'.$filter.'%');
                 })
                 ->Orwhere('invoice_number','like','%'.$filter.'%');
             })->orderBy($orderBy ?: $defaultOrderBy,$descOrAsc ?: $desc)->paginate(8);
        }
        return Invoice::with(['visit','visit.child','vaccines','dgiiSequence'])->orderBy($orderBy ?: $defaultOrderBy,$descOrAsc ?: $desc)->paginate(8);
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

    public function edit($invoice)
    {
        $_invoice = Invoice::with(['visit','visit.child','visit.child.dadOrMom','visit.child.plan.insurance','vaccines','dgiiSequence'])->whereId($invoice)->first();

        return Inertia::render('Invoices/EditOrNew',[
            'invoice' => $_invoice
        ]);
    }

    public function store(Request $request)
    {
        //TODO: validate
        //validate if visit already is invoiced

        $invoiced = Invoice::where('visit_id', $request->visit_id)->first();

        if($invoiced){
            return redirect()->route('visits.show',$request->visit_id)->with(['toast' => ['message' => 'Esta visita ya fue facturada.','success' => false]]);
        }

        //1. Create the invoice and generate invoice number
        $invoice = Invoice::create([
            'invoice_date' => Carbon::now(),
            'visit_id' => $request->visit_id,
        ]);

        $invoice->generateInvoiceNumber();

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

    public function update(Invoice $invoice, Request $request)
    {
        $date = Carbon::parse($request->invoice_date)->format('Y-m-d');
        $invoice->update([
            'invoice_number' => strtoupper($request->invoice_number),
            'invoice_date' => $date
            ]
        );
        return response()->json(['success' => true, 'message' => 'Factura actualizada correctamente']);
    }


    public function pay(Invoice $invoice, Request $request)
    {
        $vaccines =[];
        foreach ($request->vaccines as $vaccine)
        {
            $vaccines[$vaccine['id']] = ['price' => $vaccine['pivot']['price']];
        }

        $invoice->vaccines()->sync($vaccines, false);

        //2. Set discount, payment method, authorizacion number and payment_status ('Pago')
        $invoice->update([
            'discount' => $request->discount,
            'payment_method' => $request->payment_method,
            'authorization_number' => $request->authorization,
            'payment_status' => 'Pago'
        ]);
        return redirect()->route('invoices.detail',$invoice->id)->with(['toast' => ['message' => 'Factura Pagada correctamente','success' => true]]);
    }

    public function xls(Request $request)
    {
        return response()->json(['success' => true, 'invoices' => []]);
    }
}
