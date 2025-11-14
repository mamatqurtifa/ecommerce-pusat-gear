<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Menggunakan Spatie Laravel Permission
        if (!$user->hasRole('customer') && !$user->hasAnyRole(['super-admin', 'admin'])) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}