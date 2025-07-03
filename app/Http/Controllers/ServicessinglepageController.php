<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicesSinglePage;

class ServicessinglepageController extends Controller
{
    public function index()
    {
        $servicessinglepage = ServicesSinglePage::first();
        return view('services_single_page', compact('servicessinglepage'));
    }
}
