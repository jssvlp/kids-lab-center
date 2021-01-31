<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [ 'invoice_number','invoice_date',
                            'discount','visit_id','payment_method',
                            'authorization_number','payment_status'
                        ];



    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public static function getPaymentsByType($from, $to)
    {
        return DB::select("select payment_method as paymentMethod, count(id) as pagos from invoices where invoice_date between '$from' and '$to' and payment_status = 'Pago' group by  payment_method");
    }

    public static function getSumDailyPayments($from,$to)
    {
        return DB::select("select ROUND(sum(det.price - ((inv.discount / 100) * det.price)),2) mount, inv.invoice_date as invoiceDate from invoices inv join invoice_details det on (inv.id = det.invoice_id) where inv.invoice_date between '$from' and '$to' and inv.payment_status = 'Pago' group by inv.invoice_date");
    }

    public function vaccines()
    {
        return $this->belongsToMany(Vaccine::class,'invoice_details',
                    'invoice_id','vaccine_id')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
