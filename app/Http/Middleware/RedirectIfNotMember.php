<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotMember
{
    public function handle($request, Closure $next, $guard = "member")
    {
        if (!auth()->guard($guard)->check()) {
            return redirect(route('loginMember'));
        }
        return $next($request);
    }
}
