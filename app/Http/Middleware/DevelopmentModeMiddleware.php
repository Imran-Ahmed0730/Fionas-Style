<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DevelopmentModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if Development Mode is enabled
        if (getSetting('development_mode') == 1) {

            // Allow Admin Routes (assuming default admin prefix pattern)
            if ($request->is('admin') || $request->is('admin/*')) {
                return $next($request);
            }

            // Allow Login/Logout/Register routes so admins can log in
            if (
                $request->is('login') || $request->is('login/*') ||
                $request->is('register') || $request->is('register/*') ||
                $request->is('logout')
            ) {
                return $next($request);
            }

            // Allow if user is logged in as Admin (role == 1)
            // Note: This requires session/auth middleware to run before this.
            if (Auth::check() && Auth::user()->role == 1) {
                return $next($request);
            }

            // Otherwise, redirect to specialized maintenance page
            if ($request->is('/')) {
                return response()->view('frontend.maintenance');
            }

            return redirect('/');
        }

        return $next($request);
    }
}
