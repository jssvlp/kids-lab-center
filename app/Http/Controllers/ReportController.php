<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $from = Carbon::now()->addMonth(-1)->format('Y-m-d');
        $to   = Carbon::now()->format('Y-m-d');

        $invoices = $this->searchData($from,$to);

        $paymentsByType = Invoice::getPaymentsByType($from,$to);
        $sumDailyPayments = Invoice::getSumDailyPayments($from,$to);
        $invoicesForExcel = $this->getDataForExcel($from, $to);

        return Inertia::render('Reports/Reports',[
            'invoices' => $invoices,
            'paymentsByType' => $paymentsByType,
            'sumDailyPayments' => $sumDailyPayments,
            'invoicesForExcel' => $invoicesForExcel,
            'filter' => ['from' => $from, 'to' => $to]
        ]);
    }

    public function filter(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $invoices = $this->searchData($from,$to);
        $paymentsByType = Invoice::getPaymentsByType($from,$to);
        $sumDailyPayments = Invoice::getSumDailyPayments($from,$to);
        $invoicesForExcel = $this->getDataForExcel($from, $to);

        return response()->json([
            'success' => true,
            'invoices' => $invoices,
            'paymentsByType'=> $paymentsByType,
            'sumDailyPayments' => $sumDailyPayments,
            'invoicesForExcel' => $invoicesForExcel,
            'filter' => [
                'from' => $from, 'to' => $to
            ]
        ]);
    }

    private function searchData($from, $to)
    {
        return Invoice::with('vaccines')->whereDate('invoice_date', '>=', $from)
                                             ->whereDate('invoice_date', '<=', $to)
                                             ->where('payment_status','=','Pago')
                                             ->get();
    }

    public function getDataForExcel($from, $to)
    {
        return DB::table('invoice_details')
            ->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->whereDate('invoices.invoice_date', '>=', $from)
            ->whereDate('invoices.invoice_date', '<=', $to)
            ->groupBy('invoice_details.invoice_id')
            ->select(DB::raw("invoices.invoice_number AS factura,
                        invoices.invoice_date AS fecha,
                        invoices.payment_method AS 'metodo de pago',
                        invoices.authorization_number AS 'numero de autorizacion',
                        (invoices.discount / 100) * SUM(invoice_details.price) AS 'cobertura ars',
                        SUM(invoice_details.price) - (invoices.discount / 100) * SUM(invoice_details.price) as diferencia,
                        SUM(invoice_details.price) AS monto"))
            ->get();


    }
}
