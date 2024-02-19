<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Homecontroller extends Controller
{
    public function index()
    {
        return view('who-we-are.about-us');
    }

    public function history()
    {
        return view('why-we-exist.history');
    }
}
