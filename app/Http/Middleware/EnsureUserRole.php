<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::user()->type != ($role)) {
            return redirect('/');//en este punto el usuario ya debe estar autenticado pero no tiene permiso para entrar, por eso se le redirige a home y no a login
        }

        return $next($request);
    }
}
