<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Uso nas rotas: middleware('role:admin')
     *              middleware('role:admin,hotel_manager')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}