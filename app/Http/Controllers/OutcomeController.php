<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use App\Models\OutcomeRow;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Carrier;
use App\Models\Regime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutcomesExport;
use App\Exports\OutcomesCustomerExport;
use App\Models\BundleType;
use App\Models\LoadOrder;
use PDF;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientes = Customer::All();
        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 15;
        $otros = $request->txtOtros ?? "";
        $can_delete = Auth::user()->canDeleteOutcome();
        
        $salidas = $this->get_Outcomes_obj($cliente_ids, $rango, $otros);
        
        return view('intern.salidas.index', [
            'outcomes' => $salidas,
            'can_delete' => $can_delete,
            'clientes' => $clientes,
            'cliente' => $cliente,
            'rango' => $rango,
            'otros' => $otros,
        ]);
    }

    public function index_customer(Request $request)
    {
        $cliente = explode(",",Auth::user()->customer_ids);
        $rango = $request->txtRango ?? 15;
        $otros = $request->txtOtros ?? "";
        $salidas = $this->get_Outcomes_obj($cliente, $rango, $otros);
        
        return view('customer.salidas.index', [
            'outcomes' => $salidas,
            'cliente' => $cliente,
            'rango' => $rango,
            'otros' => $otros,
        ]);
    }

    public function get_Outcomes_obj(array $cliente, string $rango, string $otros)
    {
        $salidas = Outcome::whereDate('cdate', '>=', now()->subDays(intval($rango))->setTime(0, 0, 0)->toDateTimeString());

        //en caso de el el campo "otros" sea un numeo de parte o de entrada buscaremos
        $partidas_filtradas = array();
        $salidas_aux = $salidas;
        $salidas_aux = $salidas_aux->get();
        foreach ($salidas_aux as $salida) 
        {
            $outcome_rows = $salida->outcome_rows;
            foreach ($outcome_rows as $outcome_row) 
            {
                $income_row = $outcome_row->income_row;
                if($income_row->part_number()->name == $otros || $income_row->income->getIncomeNumber() == $otros)
                {
                    array_push($partidas_filtradas, $outcome_row->outcome_id);
                }
            }
        }
        $sql_whereIn = "";
        if(count($partidas_filtradas) > 0)
        {
            $sql_whereIn = "or id IN(".implode(",",$partidas_filtradas).")";
        }

        //en caso de el el campo "otros" sean una salida buscaremos

        $yearOtc="";
        $numOtc="";

        if(strlen($otros) >= 9)
        {
            $yearOtc=substr($otros,0,4);
            $numOtc=substr($otros,4,5);
            if(!(is_numeric($yearOtc) && is_numeric($numOtc)))
            {
                $yearOtc="";
                $numOtc="";
            }
        }

        if($yearOtc != "")
        {
            $salidas = $salidas->whereRaw(' ( (year = '.$yearOtc.' and number = '.$numOtc.' ) or invoice LIKE "%'.$otros.'%" or pediment LIKE "%'.$otros.'%" or reference LIKE "%'.$otros.'%" '.$sql_whereIn.') ');
        }
        else
        {
            $salidas = $salidas->whereRaw(' (invoice LIKE "%'.$otros.'%" or pediment LIKE "%'.$otros.'%" or reference LIKE "%'.$otros.'%" '.$sql_whereIn.') ');
        }

        $salidas = $salidas->orderBy('cdate', 'desc')->get();
        return $salidas;
    }

    public function download_outcomes_xls(Request $request)
    {
        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 30;
        $otros = $request->txtOtros ?? "";

        $salidas = $this->get_Outcomes_obj($cliente_ids, $rango, $otros);

        foreach ($salidas as $salida) {
            $salida->outcome_rows;
        }
        
        $export = new OutcomesExport($salidas);
        return Excel::download($export, 'reporte_de_salidas.xlsx');
    }
    public function download_outcomes_xls_customer(Request $request)
    {
        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 30;
        $otros = $request->txtOtros ?? "";

        $salidas = $this->get_Outcomes_obj($cliente_ids, $rango, $otros);

        //foreach ($salidas as $salida) {
        //    $salida->outcome_rows;
        //}
        
        $export = new OutcomesCustomerExport($salidas);
        return Excel::download($export, 'reporte_de_salidas.xlsx');
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
        $tipos_de_bulto = BundleType::All();

        return view('intern.salidas.create', [
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'regimes' => $regimes,
            'tipos_de_bulto' => $tipos_de_bulto,
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
        $tipos_de_bulto = BundleType::All();
        
        
        return view('intern.salidas.create', [
            'outcome' => $outcome,
            'numero_de_salida' => $numero_de_salida,
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'regimes' => $regimes,
            'tipos_de_bulto' => $tipos_de_bulto,
        ]);
    }

    public function loadOC(LoadOrder $load_order)
    {
        $outcome = new Outcome;
        $outcome->regime = $load_order->regimen;
        $outcome->observations = $load_order->notes;
        $outcome->customer_id = $load_order->customer_id;

        $clientes = Customer::All();
        $transportistas = Carrier::All();
        $regimes = Regime::All();
        $tipos_de_bulto = BundleType::All();
        
        return view('intern.salidas.create', [
            'outcome' => $outcome,
            'numero_de_salida' => "",
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'regimes' => $regimes,
            'tipos_de_bulto' => $tipos_de_bulto,
            'load_order' => $load_order,
        ]);
    }

    public function set_oc_status(LoadOrder $load_order, string $outcome_number)
    {
        $load_order->status = $outcome_number;
        $load_order->save();
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
    public function delete(Outcome $outcome)
    {
        $path = 'public/salidas/'.$outcome->getOutcomeNumber(false);
        if($outcome->delete())
        {
            Storage::deleteDirectory($path);
        }
    }

    public function can_change_customer(Outcome $outcome)
    {
        //esta funcion es para evitar que el usuario cambie el cliente de una entrada si esta ya cuenta con partidas asociadas a otro cliente
        $outcome_rows = $outcome->outcome_rows;
        $has_rows = (count($outcome_rows) > 0);
        $customer = $outcome->customer_id;
        if($has_rows)
        {
            $customer = $outcome_rows[0]->income_row->part_number()->customer_id;
        }
        
        return response()->json([
            'original_customer' => $customer,
            'outcome_rows_count' => count($outcome_rows),
            'has_rows' => $has_rows,
        ]);
    }

    public function downloadPDF(Outcome $outcome)
    {
        $numero_de_salida = $outcome->getOutcomeNumber(true);
        $outcome->outcome_rows; //<- se llama esta linea con el fin de cargar las partidas de esta salida

        $pdf = PDF::loadView('intern.salidas.pdf', compact('outcome'))->setPaper('a4', 'landscape');
        return $pdf->stream();
        //return $pdf->download($numero_de_salida.'.pdf');
    }
    public function downloadPDFCustomer(Outcome $outcome)
    {
        $cliente = explode(",",Auth::user()->customer_ids)[0];
        if($outcome->customer->id == $cliente)
        {
            $numero_de_salida = $outcome->getOutcomeNumber(true);
            $outcome->outcome_rows; //<- se llama esta linea con el fin de cargar las partidas de esta salida

            $pdf = PDF::loadView('intern.salidas.pdf', compact('outcome'))->setPaper('a4', 'landscape');
            return $pdf->stream();
            //return $pdf->download($numero_de_salida.'.pdf');
        }
    }

    public function test(Outcome $outcome)
    {
        return $outcome->getIncomes();
    }
}
