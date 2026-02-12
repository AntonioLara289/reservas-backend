<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Forzamos el Accept JSON
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // 2. Si el código es 401, personalizar el JSON y añadir CORS
        if ($response->getStatusCode() === 401) {
            $customResponse = response()->json([
                'status' => 'error',
                'message' => 'No se encontró un token válido o tu sesión expiró.',
                'data' => null
            ], 401);

            // 3. ¡IMPORTANTE! Copiar las cabeceras de CORS a la nueva respuesta
            return $this->addCorsHeaders($customResponse);
        }

        return $response;
    }

    /**
     * Añade cabeceras CORS básicas para que el navegador no bloquee el error
     */
    private function addCorsHeaders(Response $response): Response
    {
        $response->headers->set('Access-Control-Allow-Origin', '*'); // O tu URL de Angular
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept');
        
        return $response;
    }
}