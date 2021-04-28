<?php

namespace App\Http\Controllers;

use App\Models\DgiiNumberingConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DgiiController extends Controller
{
    public function index()
    {
        return Inertia::render('DgiiNumbering/All',[
            /* 'numerations' => DgiiNumberingConfig::all() */
        ]);
    }

    public function all()
    {
        return DgiiNumberingConfig::orderBy('created_at', 'desc')->paginate(8);
    }


    public function store(Request $request)
    {
        $sequences = DgiiNumberingConfig::count();
        $anySequenceConfigActive = DgiiNumberingConfig::where('active',true)->first();

        $active = 0;
        if($sequences === 0 || $anySequenceConfigActive === null){
            $active = 1;
        }
        //TODO: validate
        DgiiNumberingConfig::create([
            'init' => $request->init,
            'end' => $request->end,
            'config_date' => Carbon::now()->format('Y-m-d'),
            'active' => $active
        ]);

        return redirect()->route('invoices.config')->with(['toast' => ['message' => 'Secuencia  generada correctamente','success' => true]]);
    }

    public function update(DgiiNumberingConfig $config, Request $request)
    {
        //TODO: validate if cofing has not any invoices before update
        $config->update([
            'invoice_number' => strtoupper($request->invoice_number),
            'invoice_date' => $date
            ]
        );
        return response()->json(['success' => true, 'message' => 'Factura actualizada correctamente']);
    }

    public function delete(DgiiNumberingConfig $config)
    {
        //TODO: validate if cofing has not any invoices before update
        $config->delete();
        return redirect()->route('invoices.config')->with(['toast' => ['message' => 'Configuración eliminada correctamente','success' => true]]);

    }
}
