<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Carrier;
use App\Models\Regime;
use Illuminate\Support\Facades\Auth;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $can_delete = Auth::user()->canDeleteIncome();
        $clientes = Customer::All();

        $cliente = $request->txtCliente ?? 0;
        $rango = $request->txtRango ?? 30;
        $otros = $request->txtOtros ?? "";

        //$salidas = Outcome::all();
        $can_delete = Auth::user()->canDeleteOutcome();


        $salidas = Outcome::whereDate('cdate', '>=', now()->subDays(intval($rango))->setTime(0, 0, 0)->toDateTimeString());
        if($cliente > 0)
        {
            $salidas = $salidas->where('customer_id',$cliente);
        }
        $salidas = $salidas->orWhere('invoice', 'like', '%'.$otros.'%')
            ->orWhere('pediment', 'like', '%'.$otros.'%')
            ->orWhere('reference', 'like', '%'.$otros.'%')->get();


        return view('intern.salidas.index', [
            'outcomes' => $salidas,
            'can_delete' => $can_delete,
            'clientes' => $clientes,
            'cliente' => $cliente,
            'rango' => $rango,
            'otros' => $otros,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Customer::All();
        $transportistas = Carrier::All();
        $regimes = Regime::All();
        return view('intern.salidas.create', [
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'regimes' => $regimes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $salida = null;
        if(is_null($request->txtNumSalida))
        {
            $salida = new Outcome;
        }
        else
        {
            $yearOtc=substr($request->txtNumSalida,0,-5);
            $numOtc=substr($request->txtNumSalida,4);
            $salida = Outcome::where('year', $yearOtc)->where('number', $numOtc)->first();
            if(is_null($salida))
            {
                $salida = new Outcome;
            }
        }
        $salida->regime = $request->txtRegimen;
        $salida->cdate = $request->txtFecha;
        $salida->customer_id = $request->txtCliente;
        $salida->carrier_id = $request->txtTransportista;
        $salida->trailer = $request->txtCaja ?? "";
        $salida->seal = $request->txtSello ?? "";
        $salida->observations = $request->txtObservaciones ?? "";
        $salida->invoice = $request->txtFactura ?? "";
        $salida->pediment = $request->txtPedimento ?? "";
        $salida->reference = $request->txtReferencia ?? "";
        $salida->user = Auth::user()->name;
        $salida->received_by = $request->txtRecibidoPor ?? "";
        $salida->plate = $request->txtPlacas ?? "";
        $salida->leave = "2020-01-01 00:00:01";
        $salida->discount = isset($request->chkDescontar);
        
        if(is_null($salida->id))
        {
            //asignar numero de salida
            $salida->year = date("Y");
            $number = Outcome::where('year',$salida->year)->max('number');
            $salida->number = (is_null($number)) ? 1 : $number + 1;
        }
        $salida->save();
        $numero_de_salida = $salida->year.str_pad($salida->number,5,"0",STR_PAD_LEFT);

        return response()->json([
            'numero_de_salida' => $numero_de_salida,
            'id_salida' => $salida->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function show(string $numero_de_salida)
    {
        $yearOtc=substr($numero_de_salida,0,-5);
        $numOtc=substr($numero_de_salida,4);
        $outcome = Outcome::where('year', $yearOtc)->where('number', $numOtc)->first();

        $clientes = Customer::All();
        $transportistas = Carrier::All();
        $regimes = Regime::All();
        
        return view('intern.salidas.create', [
            'outcome' => $outcome,
            'numero_de_salida' => $numero_de_salida,
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'regimes' => $regimes,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function edit(Outcome $outcome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outcome $outcome)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outcome  $outcome
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outcome $outcome)
    {
        //
    }
}
