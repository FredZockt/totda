<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('search.index', [
            'search_term' => session()->get('search_term'),
            'results' => session()->get('results')
        ]);
    }

    public function search(Request $request) {
        $validator = $request->validate([
            'search_term'.session()->get('session_hash') => 'required|min:3|max:255|string|alpha_num',
        ]);
        $search_term = $validator['search_term'. session()->get('session_hash')];
        $results = User::where('name', 'like', '%' . $search_term . '%')->get();

        return redirect()->back()->with([
            'search_term' => $search_term,
            'results' => $results
        ]);
    }

}
