<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class admin_can_check
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
        if (request()->user()->role == 'checker') {
            return $next($request);
        }
        return response()->json([
            "message" => "you dont have access"
        ], 401);
    }
}
