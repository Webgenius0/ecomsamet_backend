<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if user is authenticated
        // dd(Auth::user());
        if (!Auth::check()) {
            // dd(Auth::user());
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if user has the correct role
        if (Auth::user()->role !== $role) {
            return response()->json(['error' => 'Access denied. Role required: ' . $role], 403);
        }

        return $next($request);
    }
}
