<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\MeasurementUnit;
use App\Models\Income;
use App\Models\Carrier;
use App\Models\Supplier;
use App\Models\BundleType;
use Illuminate\Support\Facades\Session;

class PartNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PartNumber::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function popup()
    {
        return "nose";
        //return view('intern.part_number.create', [
        //    'part_number' => $part_number
        //]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //NO USAR ESTE METODO PARA UPDATES
        //para update se deben usar las funciones in-line del formulario correspondiente.
        $part_number = PartNumber::where('part_number',$request->txtNumeroDeParte)->where('customer_id',$request->txtCliente)->first();
        if($part_number)
        {
            if(strlen($request->from_Incomes) > 0 )
            {
                Session::flash('part_number', $part_number);
                return redirect('/int/entradas/'.$request->from_Incomes);
            }
            return "El numero de parte ya existe. " . $part_number->id;
        }
        $part_number = new PartNumber;
        $part_number->part_number = strtoupper($request->txtNumeroDeParte);
        $part_number->customer_id = $request->txtCliente;
        $part_number->um = $request->txtUM;
        $part_number->unit_weight = $request->txtPesoUnitario;
        $part_number->desc_ing = $request->txtDescIng;
        $part_number->desc_esp = $request->txtDescEsp;
        $part_number->origin_country = $request->txtPais;
        $part_number->fraccion = $request->txtFraccion;
        $part_number->nico = $request->txtNico;
        $part_number->brand = $request->txtMarca ?? "";
        $part_number->model = $request->txtModelo ?? "";
        $part_number->serial = $request->txtSerie ?? "";
        $part_number->imex = $request->txtIMMEX ?? "";
        $part_number->fraccion_especial = $request->txtObservacionesFraccion ?? "";
        $part_number->regime = $request->txtRegimen ?? "";
        $part_number->warning = 0;

        $part_number->save();

        if(strlen($request->from_Incomes) > 0 )
        {
            Session::flash('part_number', $part_number);
            return redirect('/int/entradas/'.$request->from_Incomes);
        }
        return "Registrado: " . $part_number->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartNumber  $partNumber
     * @return \Illuminate\Http\Response
     */
    public function show(PartNumber $partNumber)
    {
        return "operacion no permitida";//PartNumber::where("part_number",$partNumber)->first();
    }
    public function getInfo(string $partNumber, string $customer)
    {
        return PartNumber::where("part_number",$partNumber)->where("customer_id",$customer)->first();
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartNumber  $partNumber
     * @return \Illuminate\Http\Response
     */
    public function edit(string $partNumber, string $customer, string $numEntrada)
    {
        //NO USAR ESTE METODO PARA UPDATES
        //para update se deben usar las funciones in-line del formulario correspondiente.
        $clientes = Customer::All();
        $ums = MeasurementUnit::All();
        return view('intern.part_number.create', [
            'part_number' => $partNumber,
            'clientes' => $clientes,
            'cliente' => $customer,
            'unidades_de_medida' => $ums,
            'from_income' => $numEntrada,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartNumber  $partNumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartNumber $partNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartNumber  $partNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartNumber $partNumber)
    {
        //
    }
}
