<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function addResident()
    {
        //dd($student);
        return view('population.registration');
    }
}
