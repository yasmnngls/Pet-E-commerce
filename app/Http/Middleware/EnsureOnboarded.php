<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && is_null(auth()->user()->onboarded_at)) {
            return redirect()->route('onboarding.show');
        }
        return $next($request);
    }
}