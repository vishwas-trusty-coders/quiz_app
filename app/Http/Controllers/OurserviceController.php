<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ourservicespage;

class OurserviceController extends Controller
{
    public function index()
    {
        $ourservicespage = Ourservicespage::first();
        return view('ourservices', compact('ourservicespage'));
    }
}
