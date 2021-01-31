<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        $from = Carbon::now()->addMonth(-1)->format('Y-m-d');
        $to   = Carbon::now()->format('Y-m-d');

        $invoices = $this->searchData($from,$to);

        $paymentsByType = Invoice::getPaymentsByType($from,$to);
        $sumDailyPayments = Invoice::getSumDailyPayments($from,$to);
            
        return Inertia::render('Reports/Reports',[
            'invoices' => $invoices,
            'paymentsByType' => $paymentsByType,
            'sumDailyPayments' => $sumDailyPayments,
            'filter' => ['from' => $from, 'to' => $to]
        ]);
    }

    public function filter(Request $request)
    {
        $from = Carbon::parse($request->from)->addDay(-1);
        $to = Carbon::parse($request->to)->addDay(-1);
        $invoices = $this->searchData($from,$to);
        $paymentsByType = Invoice::getPaymentsByType($from,$to);
        $sumDailyPayments = Invoice::getSumDailyPayments($from,$to);

        return response()->json(['success' => true, 'invoices' => $invoices, 'paymentsByType'=> $paymentsByType,'sumDailyPayments' => $sumDailyPayments,'filter' => ['from' => $from, 'to' => $to]]);
    }

    private function searchData($from, $to)
    {
        return Invoice::with('vaccines')->whereDate('invoice_date', '>=', $from)
                                             ->whereDate('invoice_date', '<=', $to)
                                             ->where('payment_status','=','Pago')
                                             ->get();
    }
}
