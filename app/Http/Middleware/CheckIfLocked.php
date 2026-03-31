<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfLocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->get('locked', false)) {
            // Allow access only to the lock screen
            if ($request->route()->getName() !== 'lock-screen') {
                return redirect()->route('lock-screen');
            }
        }
        // Allow the request to proceed if not locked.
        return $next($request);
    }
}
