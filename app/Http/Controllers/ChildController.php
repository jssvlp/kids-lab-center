<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChildController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Children/All',[
            'children' => Child::with('dadOrMom')->get()
        ]);
    }
}
