<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\IncomeRow;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Carrier;
use App\Models\Supplier;
use App\Models\MeasurementUnit;
use App\Models\BundleType;
use Illuminate\Support\Facades\Session;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$entradas = Entrada::all();
        //return view('entradas.index', ['entradas' => $entradas]);
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
        $proveedores = Supplier::All();
        $ums = MeasurementUnit::All();
        $umb = BundleType::All();
        return view('intern.entradas.create', [
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'proveedores' => $proveedores,
            'unidades_de_medida' => $ums,
            'tipos_de_bulto' => $umb,
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
        $entrada = null;
        if(is_null($request->txtNumEntrada))
        {
            $entrada = new Income;
        }
        else
        {
            $yearInc=substr($request->txtNumEntrada,0,-5);
            $numInc=substr($request->txtNumEntrada,4);
            $entrada = Income::where('year', $yearInc)->where('number', $numInc)->first();
            if(is_null($entrada))
            {
                $entrada = new Income;
            }
        }
        $entrada->cdate = $request->txtFecha;
        $entrada->customer_id = $request->txtCliente;
        $entrada->carrier_id = $request->txtTransportista;
        $entrada->supplier_id = $request->txtProveedor;
        $entrada->reference = $request->txtReferencia;
        $entrada->trailer = $request->txtCaja;
        $entrada->seal = $request->txtSello;
        $entrada->observations = $request->txtObservaciones;
        $entrada->impoExpo = $request->txtImpoExpo;
        $entrada->invoice = $request->txtFactura;
        $entrada->tracking = $request->txtTracking;
        $entrada->po = $request->txtPO;
        $entrada->sent = false;
        $entrada->user = Auth::user()->name;
        $entrada->reviewed = isset($request->chkRev);
        $entrada->reviewed_by = $request->txtActualizadoPor;
        $entrada->closed = false;
        $entrada->urgent = isset($request->chkUrgente);
        $entrada->onhold = isset($request->chkOnhold);
        $entrada->type = $request->txtClasificacion;
        if(is_null($entrada->id))
        {
            //asignar numero de entrada
            $entrada->year = date("Y");
            $number = Income::where('year',$entrada->year)->max('number');
            $entrada->number = (is_null($number)) ? 1 : $number + 1;
        }
        $entrada->save();
        $numero_de_entrada = $entrada->year.str_pad($entrada->number,5,"0",STR_PAD_LEFT);

        return response()->json([
            'numero_de_entrada' => $numero_de_entrada,
            'id_entrada' => $entrada->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(string $numero_de_entrada)
    {
        $yearInc=substr($numero_de_entrada,0,-5);
        $numInc=substr($numero_de_entrada,4);
        $income = Income::where('year', $yearInc)->where('number', $numInc)->first();

        $clientes = Customer::All();
        $transportistas = Carrier::All();
        $proveedores = Supplier::All();
        $ums = MeasurementUnit::All();
        $umb = BundleType::All();
        $part_number = null;
        if (Session::has('part_number'))
        {
            $part_number = Session::get('part_number');
        }
        return view('intern.entradas.create', [
            'income' => $income,
            'numero_de_entrada' => $numero_de_entrada,
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'proveedores' => $proveedores,
            'unidades_de_medida' => $ums,
            'tipos_de_bulto' => $umb,
            'part_number' => $part_number,
            'income_rows' => $income->income_rows,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }
}
