<?php

namespace App\Http\Middleware;

use Closure;

class EnsurePostSession
{
    public function handle($request, Closure $next)
    {
        if (
            !session()->has('user_name') ||
            !session()->has('car') ||
            !session()->has('dates')
        ) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}