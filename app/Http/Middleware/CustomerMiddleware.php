<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        } else if (Auth::user()->role != 3) {
            if (Auth::user()->role == 1 || Auth::user()->role == 4) {
                return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access to customer panel.');
            }
            Auth::logout();
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }
        return $next($request);
    }
}
