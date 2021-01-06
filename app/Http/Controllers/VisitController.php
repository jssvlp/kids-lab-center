<?php

namespace App\Http\Controllers;

use App\Models\Visit;
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
            'visit' => Visit::with(['vaccines','child'])->find($id)
        ]);
    }

    public function newOrEdit($id = null)
    {
        $visit =  (object) [];
        
        $title = 'Nueva visita';

        if($id != null){
            $visit = Visit::with(['vaccines','child'])->find($id);
            $title = 'Editar visita';
        }

        return Inertia::render('Visits/EditOrNew',[
            'visit' => $visit,
            'title' => $title
        ]);
    }
}
