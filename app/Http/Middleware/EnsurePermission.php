<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next, $role)
    {
        if (!str_contains(Auth::user()->permits, $role)) {
            return redirect('/');//en este punto el usuario ya debe estar autenticado pero no tiene permiso para entrar, por eso se le redirige a home y no a login
        }

        return $next($request);
    }
}
