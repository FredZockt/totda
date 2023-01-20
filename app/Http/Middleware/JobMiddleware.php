<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class JobMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $job_date = $user->work_finished_at;

        if($job_date) {
            // job is done
            if(Carbon::createFromTimeString($job_date)->timestamp <= Carbon::now()->timestamp) {
                session()->forget([
                    'active_job_headline',
                    'active_job_description'
                ]);
                // finish job
                $user->job()->first()->finish($user);
            }
        }

        return $next($request);
    }
}
