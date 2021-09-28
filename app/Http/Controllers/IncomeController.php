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

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncomesExport;
use App\Exports\IncomesCustomerExport;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $can_delete = Auth::user()->canDeleteIncome();
        $clientes = Customer::All();

        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 30;
        $tracking = $request->txtTracking ?? "";
        $en_inventario = isset($request->chkInventario);

        $entradas = $this->get_Incomes_obj($cliente_ids,$rango,$tracking,$en_inventario,false);
                
        return view('intern.entradas.index', [
            'incomes' => $entradas,
            'can_delete' => $can_delete,
            'clientes' => $clientes,
            'cliente' => $cliente,
            'rango' => $rango,
            'tracking' => $tracking,
            'en_inventario' => $en_inventario,
        ]);
    }

    public function download_incomes_xls(Request $request)
    {
        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 30;
        $tracking = $request->txtTracking ?? "";
        $en_inventario = isset($request->chkInventario);

        $entradas = $this->get_Incomes_obj($cliente_ids,$rango,$tracking,$en_inventario,false);
        foreach ($entradas as $income) {
            $income->income_rows;
        }
        
        $export = new IncomesExport($entradas);
        return Excel::download($export, 'reporte_de_entradas.xlsx');
    }
    public function download_incomes_xls_customer(Request $request)
    {
        $cliente = explode(",",Auth::user()->customer_ids);
        $rango = $request->txtRango ?? 30;
        $tracking = $request->txtTracking ?? "";
        $en_inventario = true;

        $entradas = $this->get_Incomes_obj($cliente,$rango,$tracking,$en_inventario,true);
        //foreach ($entradas as $income) {
        //    $income->income_rows;
        //}
        
        $export = new IncomesCustomerExport($entradas);
        return Excel::download($export, 'reporte_de_entradas.xlsx');
    }
    

    public function index_customer(Request $request)
    {
        $cliente = explode(",",Auth::user()->customer_ids);
        $rango = $request->txtRango ?? 30;
        $tracking = $request->txtTracking ?? "";
        $en_inventario = true;

        $entradas = $this->get_Incomes_obj($cliente,$rango,$tracking,$en_inventario,true);

        return view('customer.entradas.index', [
            'incomes' => $entradas,
            'cliente' => $cliente,
            'rango' => $rango,
            'tracking' => $tracking,
        ]);
    }

    public function get_Incomes_obj(array $cliente, string $rango, string $busqueda, bool $en_inventario, bool $enviada)
    {
        // '$cliente' en realidad es un array de los customer_id que vamos a filtrar NINGUNO DEBE SER CERO 0
        $entradas = Income::whereDate('cdate', '>=', now()->subDays(intval($rango))->setTime(0, 0, 0)->toDateTimeString())
            ->where('tracking', 'like', '%'.$busqueda.'%');

        if(strlen($busqueda) == 9)
        {
            $yearInc=substr($busqueda,0,-5);
            $numInc=substr($busqueda,4);
            $entradas = $entradas->orWhere('year', $yearInc)->where('number', $numInc);
        }
        
        if(count($cliente) > 0)
        {
            $entradas = $entradas->whereIn('customer_id',$cliente);
        }
        $entradas = $entradas->orderBy('cdate', 'desc')->get();

        if($en_inventario)
        {
            foreach ($entradas as $key => $entrada) 
            {
                $partidas = $entrada->income_rows;
                $count = 0;
                foreach ($partidas as $partida) 
                {
                    $count += ($partida->units - $partida->get_discounted_units());
                    if($count > 0)
                    {
                        break;
                    }
                }
                if($count == 0)
                {
                    $entrada->id = 0;
                }
            }
        }
        
        //discriminar las entradas con id = 0 porque no tienen inventario restante
        $entradas = $entradas->where('id', '>', 0);
        // obneter solo las enviadas (para modulo cliente)
        if($enviada)
        {
            $entradas = $entradas->where('sent', '==', true);
        }
        
        return $entradas;
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
        $entrada->reference = $request->txtReferencia ?? "";
        $entrada->trailer = $request->txtCaja ?? "";
        $entrada->seal = $request->txtSello ?? "";
        $entrada->observations = $request->txtObservaciones ?? "";
        $entrada->impoExpo = $request->txtImpoExpo ?? "";
        $entrada->invoice = $request->txtFactura ?? "";
        $entrada->tracking = $request->txtTracking ?? "";
        $entrada->po = $request->txtPO ?? "";
        
        $entrada->user = Auth::user()->name;
        $entrada->reviewed = isset($request->chkRev);
        $entrada->reviewed_by = $request->txtActualizadoPor ?? "";
        $entrada->closed = false;
        $entrada->urgent = isset($request->chkUrgente);
        $entrada->onhold = isset($request->chkOnhold);
        $entrada->type = $request->txtClasificacion ?? "";
        if(is_null($entrada->id))
        {
            //asignar numero de entrada
            $entrada->year = date("Y");
            $number = Income::where('year',$entrada->year)->max('number');
            $entrada->number = (is_null($number)) ? 1 : $number + 1;
            $entrada->sent = false;
        }
        $entrada->save();
        $numero_de_entrada = $entrada->year.str_pad($entrada->number,5,"0",STR_PAD_LEFT);

        return response()->json([
            'numero_de_entrada' => $numero_de_entrada,
            'id_entrada' => $entrada->id,
        ]);
    }

    public function can_change_customer(Income $income)
    {
        //esta funcion es para evitar que el usuario cambie el cliente de una entrada si esta ya cuenta con partidas asociadas a otro cliente
        $income_rows = $income->income_rows;
        $has_rows = (count($income_rows) > 0);
        $customer = $income->customer_id;
        if($has_rows)
        {
            $customer = $income_rows[0]->part_number()->customer_id;
        }
        
        return response()->json([
            'original_customer' => $customer,
            'income_rows_count' => count($income_rows),
            'has_rows' => $has_rows,
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

    public function quitarOnHold(Income $income)
    {
        $income->onhold = false;
        $income->save();
        EmailController::onHoldNotification($income);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //por alguna razon este metodo no funciono enviando un formulario con method 'DELETE'
        //$income->delete();
    }
    public function delete(Income $income)
    {
        //TO DO: falta verificar que las partidas no tengan salida.
        $partidas = $income->income_rows;
        $lista_de_salidas = "";
        foreach ($partidas as $partida) 
        {
            $outcomes = $partida->get_discounting_outcomes();
            foreach ($outcomes as $outcome) 
            {
                $lista_de_salidas .= " '".$outcome."'";
            }            
        }
        //si no se encuentra ninguna salida descontando a alguna de las partidas procedemos con el borrado
        if($lista_de_salidas == "")
        {
            foreach ($partidas as $partida) 
            {
                $partida->delete();           
            }
        }
        else
        {
            return "Alguna o algunas de las partidas de ésta entrada ya cuentan con salida: " . $lista_de_salidas . "<br>Verifíque con su equipo.";
        }
        
        $income->delete();
        //borramos los archivos
        Storage::deleteDirectory('public/entradas/'.$income->getIncomeNumber());
            
    }

    public function downloadPDF(Income $income)
    {
        $numero_de_entrada = $income->year.str_pad($income->number,5,"0",STR_PAD_LEFT);
        $income->income_rows; //<- se llama esta linea con el fin de cargar las partidas de esta entrada

        //return view('intern.entradas.pdf', [
        //    'income' => $income,
        //]);

        $pdf = PDF::loadView('intern.entradas.pdf', compact('income'))->setPaper('a4', 'landscape');
        return $pdf->stream();
        //return $pdf->download($numero_de_entrada.'.pdf');
    }
    public function downloadPDFCustomer(Income $income)
    {
        $cliente = explode(",",Auth::user()->customer_ids)[0];
        if($income->customer->id == $cliente)
        {
            $numero_de_entrada = $income->year.str_pad($income->number,5,"0",STR_PAD_LEFT);
            $income->income_rows; //<- se llama esta linea con el fin de cargar las partidas de esta entrada

            $pdf = PDF::loadView('intern.entradas.pdf', compact('income'))->setPaper('a4', 'landscape');
            return $pdf->stream();
            //return $pdf->download($numero_de_entrada.'.pdf');
        }
        else
        {
            abort(404);
        }
    }

    

    public function getBalance(Request $request)
    {
        $numero_de_entrada = $request->entrada ?? "";
        if($numero_de_entrada != "")
        {
            $yearInc=substr($numero_de_entrada,0,-5);
            $numInc=substr($numero_de_entrada,4);
            $income = Income::where('year', $yearInc)->where('number', $numInc)->first();

            if($income)
            {
                return view('intern.entradas.balance', [
                    'income' => $income,
                ]);
            }
            else
            {
                abort(404);
            }
        }
        else
        {
            return view('intern.entradas.balance');
        }
    }

    public function getBalancePDF(Income $income)
    {
        $numero_de_entrada = $income->year.str_pad($income->number,5,"0",STR_PAD_LEFT);
        $income->income_rows; //<- se llama esta linea con el fin de cargar las partidas de esta entrada

        foreach ($income->income_rows as $income_row) 
        {
            $descuentos = $income_row->get_discounted_units();
            $income_row->units -= $descuentos;
            // calculamos el peso neto tomando en cuenta los descuentos
            $row_part_number = $income_row->part_number();
            $income_row->net_weight = $income_row->units * $row_part_number->unit_weight;
            $income_row->income; // <- invocamos esta propiedad para que el objeto final cuente con informacion de su entrada
        }

        $pdf = PDF::loadView('intern.entradas.pdf', compact('income'))->setPaper('a4', 'landscape');
        return $pdf->download($numero_de_entrada.'.pdf');
    }
}
