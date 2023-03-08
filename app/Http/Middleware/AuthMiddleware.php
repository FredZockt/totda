<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if(is_null(session()->get('session_hash'))) {
            session()->put('session_hash', sha1(random_bytes(2)));
        }

        if (Auth::check()) {
            return $next($request);
        } else {
            return redirect()->route('login');
        }
    }
}
