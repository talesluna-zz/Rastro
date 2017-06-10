<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Cors {
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('OPTIONS')) {
            $response = new Response("", 200);
        }
        else {
            $response = $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('X-Powered-By', 'Rastro - API Rastreamento de objetos - Correios')
                ->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
        }
        return $response;
    }
}