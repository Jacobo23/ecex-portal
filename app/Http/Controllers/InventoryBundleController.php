<?php

namespace App\Http\Controllers;

use App\Models\InventoryBundle;
use App\Models\IncomeRow;
use Illuminate\Http\Request;

class InventoryBundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    
    public function edit_cantidad_bultos(IncomeRow $income_row, string $cantidad)
    {
        $inv_bundle = InventoryBundle::where('income_row_id',$income_row->id)->first();
        if($inv_bundle === null)
        {
            $inv_bundle = new InventoryBundle;
            $inv_bundle->income_row_id = $income_row->id;
        }
        $inv_bundle->quantity = $cantidad;
        $inv_bundle->save();
        return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryBundle  $inventoryBundle
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryBundle $inventoryBundle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryBundle  $inventoryBundle
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryBundle $inventoryBundle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryBundle  $inventoryBundle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryBundle $inventoryBundle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryBundle  $inventoryBundle
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryBundle $inventoryBundle)
    {
        //
    }
}
