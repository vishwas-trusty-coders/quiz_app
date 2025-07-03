<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Homepage;

class HomepageController extends Controller
{
    public function index()
    {
        $homepage = Homepage::first();
        return view('homepage', compact('homepage'));
    }
}
