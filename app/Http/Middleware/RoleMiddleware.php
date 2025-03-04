<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        \Log::info('Middleware RoleMiddleware dipanggil');
        \Log::info('User:', ['user' => auth()->user()]);
        \Log::info('Roles:', ['roles' => $roles]);

        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        if (!in_array($user->role->name, $roles)) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}