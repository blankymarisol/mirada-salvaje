<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRolVeterinario
{
    /**
     * Permite el acceso únicamente a veterinarios y administradores.
     * La información clínica del centro es un dato sensible.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! $usuario->rol || ! in_array($usuario->rol->nombre, ['veterinario', 'admin'])) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        return $next($request);
    }
}
