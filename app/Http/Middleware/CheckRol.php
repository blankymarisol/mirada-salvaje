<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRol
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
{
    $user = $request->user();

    if (!$user || !$user->rol || !in_array($user->rol->nombre, $roles)) {
        abort(403, 'No tienes permiso para acceder a este recurso.');
    }

    return $next($request);
}
}
