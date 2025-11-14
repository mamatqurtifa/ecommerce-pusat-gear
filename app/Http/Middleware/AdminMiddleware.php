<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Menggunakan Spatie Laravel Permission
        if (!$user->hasAnyRole(['super-admin', 'admin', 'staff'])) {
            abort(403, 'Unauthorized access to admin area.');
        }

        return $next($request);
    }
}