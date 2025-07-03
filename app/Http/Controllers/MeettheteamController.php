<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetOurTeamPage;

class MeettheteamController extends Controller
{
    public function index()
    {
        $meetourteampage = MeetOurTeamPage::first();
        return view('meet_the_team', compact('meetourteampage'));
    }
}
