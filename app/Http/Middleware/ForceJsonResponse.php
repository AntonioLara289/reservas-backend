<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // Forzamos la cabecera Accept para que Laravel sepa que es una API
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // Si el middleware de auth falló y no hay token, 
        // Laravel devolverá un 401. Aquí podemos capturarlo.
        if ($response->getStatusCode() === 401) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontró un token válido o tu sesión expiró.',
                'data' => null
            ], 401);
        }

        return $response;
    }
}