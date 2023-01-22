<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HighscoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $players = Cache::remember('highscore', 1440, function() {
            return User::orderBy('gold', 'desc')->take(100)->get();
        });

        return view('highscore', [
            "players" => $players
        ]);
    }
}
