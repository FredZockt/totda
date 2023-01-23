<?php

namespace App\Http\Middleware;

use App\Models\Inventory;
use Closure;
use Illuminate\Http\Request;

class SidebarMiddleware
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
        $slots = Inventory::where('user_id', auth()->user()->id)->get()->count();
        $user = auth()->user();
        $city = $user->currentCity()->first();

        if($user->job_id != 1) {
            session()->put([
                'sidebar_city_headline' => 'Your current city',
                'sidebar_city_content' => $city->name,
                'sidebar_gold_headline' => 'Your current gold',
                'sidebar_gold_content' => $user->gold,
                'sidebar_inventory_headline' => 'Your inventory',
                'sidebar_inventory_content' => $slots . ' / 32'
            ]);
        } else {
            session()->forget([
                'sidebar_city_headline',
                'sidebar_city_content'
            ]);
            session()->put([
                'sidebar_gold_headline' => 'Your current gold',
                'sidebar_gold_content' => $user->gold,
                'sidebar_inventory_headline' => 'Your inventory',
                'sidebar_inventory_content' => $slots . ' / 32'
            ]);
        }


        return $next($request);
    }
}
