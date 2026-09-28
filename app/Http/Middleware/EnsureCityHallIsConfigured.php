<?php

namespace App\Http\Middleware;

use App\Models\CityHall;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCityHallIsConfigured
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! CityHall::isConfigured()) {
            return redirect()->route('city-hall')->with('notify', [
                'type' => 'warning',
                'message' => 'Cadastre a prefeitura antes de continuar.',
            ]);
        }

        return $next($request);
    }
}
