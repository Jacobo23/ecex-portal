<?php

namespace App\Http\Controllers;

use App\Models\IncomeRow;
use App\Models\InventoryBundle;
use Illuminate\Http\Request;

class IncomeRowController extends Controller
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
        /* 
        txtNumeroDeParte
        txtNumeroDeParteID
        txtDescIng
        txtDescEsp
        txtCantidad
        txtUM
        txtBultos
        txtUMB
        txtPesoNeto
        txtPesoBruto
        txtPais
        txtFraccion
        txtNico
        txtPOPartida
        txtLocacion
        txtIMMEX
        txtMarca
        txtModelo
        txtSerie
        txtLote
        txtRegimen
        txtSkids
        txtObservacionesPartida
        */
        $incomeRow = null;
        $is_update = false;
        
        if($request->incomeRowID)
        {
            // es update
            $incomeRow = IncomeRow::find($request->incomeRowID);
            $is_update = true;
        }
        else
        {
            //es insert
            $incomeRow = new IncomeRow;
            $incomeRow->income_id = $request->incomeID ;
        }
        
        $incomeRow->part_number_id = $request->txtNumeroDeParteID ;
        $incomeRow->units = $request->txtCantidad ;
        $incomeRow->bundles = $request->txtBultos ;
        $incomeRow->umb = $request->txtUMB ;
        $incomeRow->ump = $request->txtUM ;
        $incomeRow->net_weight = $request->txtPesoNeto ;
        $incomeRow->gross_weight = $request->txtPesoBruto ;
        $incomeRow->po = $request->txtPOPartida ?? "";
        $incomeRow->desc_ing = $request->txtDescIng ;
        $incomeRow->desc_esp = $request->txtDescEsp ;
        $incomeRow->origin_country = $request->txtPais ;
        $incomeRow->fraccion = $request->txtFraccion ;
        $incomeRow->nico = $request->txtNico ?? "";
        $incomeRow->location = $request->txtLocacion ?? "";
        $incomeRow->observations = $request->txtObservacionesPartida ?? "";
        //$incomeRow->extras = "";
        $incomeRow->brand = $request->txtMarca ?? "";
        $incomeRow->model = $request->txtModelo ?? "";
        $incomeRow->serial = $request->txtSerie ?? "";
        $incomeRow->lot = $request->txtLote ?? "";
        //$incomeRow->packing_id = "";
        $incomeRow->imex = $request->txtIMMEX ?? "";
        $incomeRow->regime = $request->txtRegimen ?? "";
        $incomeRow->skids = $request->txtSkids ?? "";

        $incomeRow->save();

        if(!is_null($incomeRow->id))
        {
            //registrar bultos en inventario
            $inv_bundle = InventoryBundle::where('income_row_id',$incomeRow->id)->first();
            if($inv_bundle === null)
            {
                $inv_bundle = new InventoryBundle;
            }
            $inv_bundle->income_row_id = $incomeRow->id;
            $inv_bundle->quantity = $incomeRow->bundles;
            $inv_bundle->save();

            return response()->json([
                'msg' => "Partida guardada!",
                'is_update' => $is_update,
                'id' => $incomeRow->id,
            ]);
        }
        else
        {
            return "La partida no se pudo guardar, verifique los datos.";
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IncomeRow  $incomeRow
     * @return \Illuminate\Http\Response
     */
    public function show(IncomeRow $incomeRow)
    {
        return response()->json([
            'income_row' => $incomeRow,
            'part_number' => $incomeRow->part_number(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IncomeRow  $incomeRow
     * @return \Illuminate\Http\Response
     */
    public function edit(IncomeRow $incomeRow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IncomeRow  $incomeRow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IncomeRow $incomeRow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IncomeRow  $incomeRow
     * @return \Illuminate\Http\Response
     */
    public function destroy(IncomeRow $incomeRow)
    {
        $incomeRow->delete();
    }

    public function hasOutcomes(IncomeRow $income_row)
    {
        $response = "";
        $outcomes = $income_row->get_discounting_outcomes();
        foreach ($outcomes as $outcome) 
        {
            $response .= " '".$outcome."'";
        }
        return $response;
    }

    
}
