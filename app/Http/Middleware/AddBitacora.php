<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Bitacora;
use App\Models\Income;
use App\Models\Outcome;
use Illuminate\Support\Facades\Auth;

class AddBitacora
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
    public function handle($request, Closure $next, $action)
    {
        $registro = new Bitacora;
        $registro->usuario = Auth::user()->name;
        $registro->accion = $action;

        switch ($action) {
            case 'Eliminar Entrada':
                $income_id = explode("/",$request->path())[2];
                $income = Income::find($income_id);
                if($income)
                {
                    $registro->texto = $income->getIncomeNumber();
                }
                break;
            case 'Eliminar Salida':
                $outcome_id = explode("/",$request->path())[2];
                $outcome = Outcome::find($outcome_id);
                if($outcome)
                {
                    $registro->texto = $outcome->getOutcomeNumber(false);
                }
                break;
        }

        $registro->save();
        return $next($request);
    }
}
