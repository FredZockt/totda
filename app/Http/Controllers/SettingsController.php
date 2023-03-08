<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Building;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('/welcome');
    }
}
