<?php

namespace App\Http\Controllers;

use App\Models\LoadOrder;
use App\Models\LoadOrderRow;
use Illuminate\Http\Request;
use App\Models\Regime;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoadorderExport;

class LoadOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() // este es para la vista del cliente
    {
        $clientes = explode(",",Auth::user()->customer_ids);

        $load_orders = $this->get_load_orders_obj($clientes);
        $can_create_oc = Auth::user()->canCreateOC();

        return view('customer.ordenes_de_carga.index', [
            'load_orders' => $load_orders,
            'can_create_oc' => $can_create_oc,
        ]);
    }

    public function index_intern() // este es para la vista del cliente
    {
        $clientes = array();//el arreglo esta vacio para forzar a get_load_orders_obj() a traernos todos los clientes

        $load_orders = $this->get_load_orders_obj($clientes);

        return view('intern.ordenes_de_carga.index', [
            'load_orders' => $load_orders,
        ]);
    }

    public function get_load_orders_obj(array $clientes)
    {
        $load_orders =  LoadOrder::All();
        if(count($clientes) > 0)
        {
            $load_orders = $load_orders->whereIn('customer_id', $clientes);
        }
        $load_orders = $load_orders;
        foreach ($load_orders as $load_order) 
        {
            $load_order_rows = $load_order->load_order_rows;
            foreach ($load_order_rows as $load_order_row) 
            {
                $load_order_row->income_row;
            }
        }
        return $load_orders;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regimes = Regime::All();
        return view('customer.ordenes_de_carga.create', [
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
        $cliente = explode(",",Auth::user()->customer_ids)[0];//solo un cliente a la vez

        $load_order = new LoadOrder;
        $load_order->regimen = $request->txtRegimen;
        $load_order->customer_id = $cliente;
        $load_order->cdate = now();//now()->toDateTimeString();
        $load_order->notes = $request->txtObservaciones ?? "";

        $load_order_rows_ids=array();
        if($load_order->save())
        {
            $cout = count($request->income_row_id);
        
            for ($i = 0; $i < $cout; $i++) 
            {
                $load_order_row = new LoadOrderRow;
                $load_order_row->load_order_id = $load_order->id;
                $load_order_row->income_row_id = $request->income_row_id[$i];
                $load_order_row->units = $request->units[$i];
                $load_order_row->save();
                array_push($load_order_rows_ids,$load_order_row->id);
            }
        }

        return $load_order_rows_ids;
    }

    public function downloadOC(LoadOrder $oc)
    {
        //return "hola";
        $oc_rows = $oc->load_order_rows;
        $export = new LoadorderExport($oc_rows);
        return Excel::download($export, 'OC-'.$oc->id.'.xlsx');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoadOrder  $loadOrder
     * @return \Illuminate\Http\Response
     */
    public function show(LoadOrder $loadOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoadOrder  $loadOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(LoadOrder $loadOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoadOrder  $loadOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoadOrder $loadOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoadOrder  $loadOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoadOrder $loadOrder)
    {
        //
    }
}
