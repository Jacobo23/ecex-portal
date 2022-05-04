<?php

namespace App\Http\Controllers;

use App\Models\IncomeRow;
use App\Models\Income;
use App\Models\PartNumber;
use App\Models\InventoryBundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MassiveIncomeImport;

use App\Models\BundleType;
use App\Models\MeasurementUnit;
use App\Models\MassiveRow;




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
        $incomeRow->desc_ing = $request->txtDescIng ?? "";
        $incomeRow->desc_esp = $request->txtDescEsp ?? "";
        $incomeRow->origin_country = $request->txtPais ?? "";
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
     //por alguna razon este metodo ya no funciona porque el method DELETE no es reconocido
    //public function destroy(IncomeRow $incomeRow)
    //{
        //no hay necesidad de eliminar id de inventory_bundles porque se hace en cascada automatica
        //$incomeRow->delete();
    //}
    public function delete(IncomeRow $income_row)
    {
        //no hay necesidad de eliminar id de inventory_bundles porque se hace en cascada automatica
        $income_row->delete();
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

    public function masiva(string $income_number)
    {
        $yearInc=substr($income_number,0,-5);
        $numInc=substr($income_number,4);
        $income = Income::where('year',$yearInc)->where('number',$numInc)->first();
        $path = '/public/entradas/'.$income_number.'/temp_massive.xlsx';
        if(Storage::exists($path))
        {
            $data = Excel::toArray(new MassiveIncomeImport(), $path);
            //      S  R  C    <- Sheet  Row  Cell
            //$data[0][0][0];
            
            $obj_data = array();
            $first_iteration = true;
            foreach ($data[0] as $row) 
            {
                $validation = ""; // <- good if everythig ok, warning if part_number no existe, danger para cualquier error de conversion
                $validation_msg = ""; // explicacion sobre los errores encontrados
                if($first_iteration || strlen(trim($row[0])) < 3)
                {
                    $first_iteration = false;
                    continue;
                }
                //$obj_row = new MassiveData;
                $obj_row = new MassiveRow;
                
                $obj_row->desc_ing = trim($row[1]);
                $obj_row->desc_esp = trim($row[2]);
                $obj_row->origin_country = trim($row[3]);


                $obj_row->units = trim($row[4]);
                if(!is_numeric($obj_row->units))
                {
                    $validation = "danger";
                    $validation_msg .= " Campo <strong>Cantidad</strong> no se identfica como numérico<br> ";
                }


                $um = MeasurementUnit::where('desc',trim($row[5]))->first();
                if(!$um)
                {
                    $validation = "danger";
                    $obj_row->um = trim($row[5]);
                    $validation_msg .= " Campo <strong>Unidad de medida</strong> no se encontró en la lista<br> ";
                }
                else
                {
                    $obj_row->um = $um->desc;
                }
                
                
                $obj_row->bundles = trim($row[6]);
                if(!is_numeric($obj_row->bundles))
                {
                    $validation = "danger";
                    $validation_msg .= " Campo <strong>Bultos</strong> no se identfica como numérico<br> ";
                }

                $umb = BundleType::where('desc',trim($row[7]))->first();
                if(!$umb)
                {
                    $validation = "danger";
                    $obj_row->bundle_type = trim($row[7]);
                    $validation_msg .= " Campo <strong>tipo de bulto</strong> no se encontró en la lista<br> ";
                }
                else
                {
                    $obj_row->bundle_type = $umb->desc;
                }

                $obj_row->net_weight = trim($row[8]);
                if(!is_numeric($obj_row->net_weight))
                {
                    $validation = "danger";
                    $validation_msg .= " Campo <strong>peso neto</strong> no se identfica como numérico<br> ";
                }

                $obj_row->gross_weight = trim($row[9]);
                if(!is_numeric($obj_row->gross_weight))
                {
                    $validation = "danger";
                    $validation_msg .= " Campo <strong>peso bruto</strong> no se identfica como numérico<br> ";
                }

                $obj_row->fraccion = trim($row[10]);
                if(strlen($obj_row->fraccion) != 8 || !is_numeric($obj_row->fraccion))
                {
                    $validation = "danger";
                    $validation_msg .= " Campo <strong>fraccion</strong> no tiene 8 digitos o no son números todos<br> ";
                }

                $obj_row->nico = trim($row[11]);
                if(strlen($obj_row->nico) != 2)
                {
                    $validation = "danger";
                    $validation_msg .= " Campo <strong>nico</strong> no tiene 2 digitos<br> ";
                }

                $obj_row->po = trim($row[12]);
                $obj_row->brand = trim($row[13]);
                $obj_row->model = trim($row[14]);
                $obj_row->serial = trim($row[15]);
                $obj_row->location = trim($row[16]);
                $obj_row->regime = trim($row[17]);
                $obj_row->lot = trim($row[18]);
                $obj_row->skids = trim($row[19]);
                $obj_row->imex = trim($row[20]);
                $obj_row->observations = "";
                $obj_row->income_id = $income->id;  

                $part_number = PartNumber::where('part_number',trim($row[0]))->where('customer_id',$income->customer_id)->first();

                if($part_number)
                {
                    $obj_row->part_number_name = $part_number->part_number;
                    $obj_row->part_number_id = $part_number->id;
                }
                else
                {
                    $obj_row->part_number_name = trim($row[0]);
                    //$obj_row->part_number = $part_number;
                    $obj_row->part_number_id = "0";

                    if($validation == "")
                    {
                        $validation = "warning";
                    }
                    
                    $validation_msg = "El Número de parte &ldquo;<i><u>".$obj_row->part_number_name."</u></i>&rdquo; <strong>no existe</strong><br> " . $validation_msg;
                }

                if($validation == "")
                {
                    $validation = "good";
                }
                
                $obj_row->validation = $validation;   
                $obj_row->validation_msg = $validation_msg; 
                
                $obj_row->save();
                array_push($obj_data,$obj_row);
            }
            Storage::delete($path);
            if(sizeof($obj_data)) 
            {
                return view('intern.entradas.masiva', [
                    'income_number' => $income_number,
                    'income' => $income,
                    'excel_data' => $obj_data,
                ]);
            }            
        }
        return view('intern.entradas.masiva', [
            'income_number' => $income_number,
            'income' => $income,
        ]);
        
    }

    public function upload_masiva(Request $request)
    {
        Storage::put('/public/entradas/'.$request->fileNumEntrada.'/temp_massive.xlsx', file_get_contents($request->file('file')));
        return redirect('/income_row_massive/'.$request->fileNumEntrada);   
    }

    public function download_massive_template()
    {
        return Storage::download('public/entradas/pantilla_masiva.xlsx');
    }
    public function store_massive_rows(Income $income)
    {
        $massive_rows = MassiveRow::where('income_id',$income->id)->get();
        foreach ($massive_rows as $massive_row) {
            $this->store_massive_row($massive_row);
            $massive_row->delete();
        }
    }
    public function store_massive_row(MassiveRow $request)
    {
        // esta funcion solia ser llamada por un metodo ajax y le pasaba un objeto(Request $request) para una sola massive_income_row 05/04/2022
        // esta funcion es modificada para guardar una sola massive_income_row pasando como argumento un MassiveRow para ser llamado por un metodo de esta (this) clase
        $income_row = new IncomeRow;
        $income_row->desc_ing = $request->desc_ing;
        $income_row->desc_esp = $request->desc_esp;
        $income_row->origin_country = $request->origin_country;
        $income_row->units = $request->units;
        $income_row->ump = $request->um;
        $income_row->bundles = $request->bundles;
        $income_row->umb = $request->bundle_type;
        $income_row->net_weight = $request->net_weight;
        $income_row->gross_weight = $request->gross_weight;
        $income_row->fraccion = $request->fraccion;
        $income_row->nico = $request->nico;
        $income_row->po = $request->po ?? "";
        $income_row->brand = $request->brand ?? "";
        $income_row->model = $request->model ?? "";
        $income_row->serial = $request->serial ?? "";
        $income_row->location = $request->location ?? "";
        $income_row->regime = $request->regime ?? "";
        $income_row->lot = $request->lot ?? "";
        $income_row->skids = $request->skids ?? "";
        $income_row->imex = $request->imex ?? "";
        $income_row->income_id = $request->income_id;
        $income_row->part_number_id = $request->part_number_id;
        //si el numero de parte no existe hay que crearlo
        if($request->part_number_id == 0 || $request->part_number_id == "0" || $request->part_number_id == "")
        {
            $cliente = $income_row->income->customer_id;
            $aux_part_number = PartNumber::where('part_number',$request->part_number_name)->where('customer_id',$cliente)->first();
        
            if(!$aux_part_number)
            {
                $aux_part_number = new PartNumber;
                $aux_part_number->part_number = strtoupper($request->part_number_name);
                $aux_part_number->customer_id = $cliente;
                $aux_part_number->um = $income_row->ump;
                $aux_part_number->unit_weight = ($income_row->net_weight / $income_row->units);
                $aux_part_number->desc_ing = $income_row->desc_ing;
                $aux_part_number->desc_esp = $income_row->desc_esp;
                $aux_part_number->origin_country = $income_row->origin_country;
                $aux_part_number->fraccion = $income_row->fraccion;
                $aux_part_number->nico = $request->nico;
                $aux_part_number->brand = $request->brand ?? "";
                $aux_part_number->model = $income_row->model ?? "";
                $aux_part_number->serial = $income_row->serial ?? "";
                $aux_part_number->imex = $income_row->imex ?? "";
                $aux_part_number->fraccion_especial = "";
                $aux_part_number->regime = $income_row->regime ?? "";
                $aux_part_number->warning = 0;
                $aux_part_number->save();
            }
            $income_row->part_number_id  = $aux_part_number->id;
        }
        //guardamos
        if($income_row->save())
        {
            return $income_row->id;
        }
    }

    public function clear_income_rows(Income $income)
    {
        $income_rows = $income->income_rows;
        $disscounting_outcomes = "";
        foreach ($income_rows as $income_row) {
            $disscounting_outcomes .= implode(" ",$income_row->get_discounting_outcomes());
        }
        if($disscounting_outcomes != "")
        {
            return response()->json([
                'status' => 1,
                'msg' => "Esta entrada ya cuenta con partidas y alguna(s) de 'estas partidas ya cuentan con salida.<br>No se pudieron eliminar las partidas anteriores por causa de las siguientes salidas:<br>".$disscounting_outcomes."<br><strong>Se abortó la operación</strong>",
            ]);
        }
        foreach ($income_rows as $income_row) 
        {
            $income_row->delete();
        }
        return response()->json([
            'status' => 0,
            'msg' => "",
        ]);

    }
    

}

class MassiveData {

    public $income_id;
    public $part_number_name;
    public $part_number;
    public $desc_ing;
    public $desc_esp;
    public $origin_country;
    public $units;
    public $um;
    public $bundles;
    public $bundle_type;
    public $net_weight;
    public $gross_weight;
    public $fraccion;
    public $nico;
    public $po;
    public $brand;
    public $model;
    public $serial;

    public $location;
    public $regime;
    public $lot;
    public $skids;
    public $imex;
    public $observations;

    public $validation;
    public $validation_msg;

  }
