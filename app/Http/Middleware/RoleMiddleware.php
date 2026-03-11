<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $roles)
   
 {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Split "admin|manager" into ['admin', 'manager']
        $allowed = collect(explode('|', $roles))
            ->map(fn ($r) => trim(strtolower($r)))
            ->filter()
            ->values();

        if ($allowed->isEmpty()) {
            return response()->json(['message' => 'No roles configured'], 500);
        }

        if (!$allowed->contains(strtolower($user->role))) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }

}
