<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\MeasurementUnit;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Session;

class PartNumberController extends Controller
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
        $desc = $request->txtDesc ?? "";
        $offset = $request->txtTab ?? "1"; //<-- tab debe venir del formulario y comienza en 1, representa los tab (paginacion) que queremos consultar, despues le restaremos 1 porque en el codigo lo necesitamos base 0 pero el usuario debe verlo en base 1
        $offset--;
        $items = 100;
        $result = $this->index_object($cliente, $desc, $offset, $items);
        $part_numbers = $result[0];
        $count = $result[1]/$items;

        $can_delete = Auth::user()->canDeletePartNumber();
        $can_edit = Auth::user()->canEditPartNumber();

        return view('intern.part_number.index', [
            'part_numbers' => $part_numbers,
            'tab' => $offset+1,
            'desc' => $desc,
            'clientes' => $clientes,
            'cliente' => $cliente,
            'tab_count' => $count,
            'can_delete' => $can_delete,
            'can_edit' => $can_edit,
        ]);
    }

    public function index_object(string $cliente, string $desc, string $offset, string $items)
    {
        $obj = PartNumber::whereRaw(" ( desc_ing like '%".$desc."%' or desc_esp like '%".$desc."%' or part_number like '%".$desc."%' ) ");
        if($cliente != "0")
        {
            $obj->where('customer_id',$cliente);
        }
        $total_count = $obj->count();
        $obj = $obj->skip($items*$offset)->take($items)->get();

        return [$obj,$total_count];
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Customer::All();
        $ums = MeasurementUnit::All();

        return view('intern.part_number.create', [
            'part_number' => "",
            'clientes' => $clientes,
            'unidades_de_medida' => $ums,
            'from_income' => "",
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
        $accion = "Update Part_number";
        $part_number = PartNumber::where('part_number',$request->txtNumeroDeParte)->where('customer_id',$request->txtCliente)->first();
        //este pedazo de codigo no permitiria que se actualicen los numeros de parte si han sido registrados desde una entrada
        //se comentara para permitir que el usuario edite numeros de parte aunque lo haga dese las entradas con el modal de numeros de parte
        // if(strlen($request->from_Incomes) > 0 && $part_number)
        // {
        //     Session::flash('part_number', $part_number);
        //     return redirect('/int/entradas/'.$request->from_Incomes);
        // }
        if(!$part_number)
        {
            $part_number = new PartNumber;
            $accion = "Create Part_number";
        }

        //registrar en la bitacora
        Bitacora::registrar($accion, Auth::user()->name ,strtoupper($request->txtNumeroDeParte) . " [fraccion:" . $part_number->fraccion . "=>" . $request->txtFraccion . "]" . " [pesoU:" . $part_number->unit_weight . "=>" . $request->txtPesoUnitario . "]");
        
        $part_number->part_number = strtoupper($request->txtNumeroDeParte);
        $part_number->customer_id = $request->txtCliente;
        $part_number->um = $request->txtUM;
        $part_number->unit_weight = $request->txtPesoUnitario;
        $part_number->desc_ing = $request->txtDescIng ?? "";
        $part_number->desc_esp = $request->txtDescEsp ?? "";
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
        return redirect('/part_number');
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
    // public function getInfo(string $partNumber, string $customer)
    // {
    //     return PartNumber::where("part_number",$partNumber)->where("customer_id",$customer)->first();
    // }
    public function getInfo(Request $request, string $customer)
    {
        return PartNumber::where("part_number",$request->partNumber)->where("customer_id",$customer)->first();
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartNumber  $partNumber
     * @return \Illuminate\Http\Response
     */
    // public function edit(string $partNumber, string $customer, string $numEntrada)
    // {
    //     //NO USAR ESTE METODO PARA UPDATES
    //     //para update se deben usar las funciones in-line del formulario correspondiente.   ( edit_existing() )

    //     $clientes = Customer::All();
    //     $ums = MeasurementUnit::All();
    //     return view('intern.part_number.create', [
    //         'part_number' => $partNumber,
    //         'clientes' => $clientes,
    //         'cliente' => $customer,
    //         'unidades_de_medida' => $ums,
    //         'from_income' => $numEntrada,
    //     ]);
    // }
    public function edit(Request $request, string $customer, string $numEntrada)
    {
        //NO USAR ESTE METODO PARA UPDATES
        //para update se deben usar las funciones in-line del formulario correspondiente.   ( edit_existing() )

        $clientes = Customer::All();
        $ums = MeasurementUnit::All();
        return view('intern.part_number.create', [
            'part_number' => $request->partNumber,
            'clientes' => $clientes,
            'cliente' => $customer,
            'unidades_de_medida' => $ums,
            'from_income' => $numEntrada,
        ]);
    }

    public function edit_existing(string $partNumber_id)
    {
        $clientes = Customer::All();
        $ums = MeasurementUnit::All();
        $part_number = PartNumber::find($partNumber_id);

        return view('intern.part_number.create', [
            'part_number_obj' => $part_number,
            'part_number' => $part_number->part_number,
            'clientes' => $clientes,
            'cliente' => $part_number->customer_id,
            'unidades_de_medida' => $ums,
            'from_income' => "",
        ]);
    }

    public function edit_existing_update_mode(string $partNumber_id, string $numEntrada)
    {
        $clientes = Customer::All();
        $ums = MeasurementUnit::All();
        $part_number = PartNumber::find($partNumber_id);

        return view('intern.part_number.create', [
            'part_number_obj' => $part_number,
            'part_number' => $part_number->part_number,
            'clientes' => $clientes,
            'cliente' => $part_number->customer_id,
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
        $dependent_incomes = $partNumber->dependent_Incomes();
        if (strlen($dependent_incomes) > 0)
        {
            return "Este número de parte esta presente en una o varias entradas, por lo tanto <strong>no se puede eliminar.</strong> <br>Lista de entradas:" . $dependent_incomes;
        }
        $partNumber->delete();
        return "Eliminado!";
    }
}
