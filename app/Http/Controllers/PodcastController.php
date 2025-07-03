<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PodcastPage;

class PodcastController extends Controller
{
    public function index()
    {
        $podcastpage = PodcastPage::first();
        return view('podcast', compact('podcastpage'));
    }
}
