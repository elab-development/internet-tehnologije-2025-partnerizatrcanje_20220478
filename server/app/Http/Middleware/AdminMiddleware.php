<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user || $user->tipKorisnika !== 'admin') {
            return response()->json([
                'uspesno' => false,
                'poruka' => 'Pristup odbijen. Potrebna su administratorska prava.',
                'podaci' => null
            ], 403);
        }    
        return $next($request);
    }
}
