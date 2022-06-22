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
use App\Models\Bitacora;

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IncomesExport;
use App\Exports\IncomesCustomerExport;
use App\Models\PartNumber;
use App\Models\InventoryBundle;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date1 = date_format(date_create(now()->subDays(intval(30))->toDateTimeString()), "m/d/Y");
        $date2 = date("m/d/Y");

        $can_delete = Auth::user()->canDeleteIncome();
        $can_hide = Auth::user()->canHideIncome();
        $clientes = Customer::All();

        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);

        $rango = $request->txtRango ?? $date1 . " - " . $date2;
        $tracking = $request->txtTracking ?? "";
        $en_inventario = $request->txtStatus ?? "todo";

        $entradas = $this->get_Incomes_obj_range_dates($cliente_ids,$rango,$tracking,$en_inventario,false);
                
        return view('intern.entradas.index', [
            'incomes' => $entradas,
            'can_delete' => $can_delete,
            'can_hide' => $can_hide,
            'clientes' => $clientes,
            'cliente' => $cliente,
            'rango' => $rango,
            'tracking' => $tracking,
            'en_inventario' => $en_inventario,
        ]);
    }

    public function download_incomes_xls(Request $request)
    {
        $date1 = date_format(date_create(now()->subDays(intval(30))->toDateTimeString()), "m/d/Y");
        $date2 = date("m/d/Y");

        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? $date1 . " - " . $date2;
        $tracking = $request->txtTracking ?? "";
        $en_inventario = $request->txtStatus ?? "todo";

        $entradas = $this->get_Incomes_obj_range_dates($cliente_ids,$rango,$tracking,$en_inventario,false);
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
        
        $export = new IncomesCustomerExport($entradas);
        return Excel::download($export, 'reporte_de_entradas.xlsx');
    }
    

    public function index_customer(Request $request)
    {
        $cliente = explode(",",Auth::user()->customer_ids);
        $rango = $request->txtRango ?? 30;
        $tracking = $request->txtTracking ?? "";
        $en_inventario = true;

        $can_quit_onhold = Auth::user()->canQuitOnhold();

        $entradas = $this->get_Incomes_obj($cliente,$rango,$tracking,$en_inventario,true);

        return view('customer.entradas.index', [
            'incomes' => $entradas,
            'cliente' => $cliente,
            'rango' => $rango,
            'tracking' => $tracking,
            'can_quit_onhold' => $can_quit_onhold,
        ]);
    }

    public function get_Incomes_obj(array $cliente, string $rango, string $busqueda, bool $en_inventario, bool $for_customer)
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
        $entradas = $entradas->orderBy('id', 'desc')->get();

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
        if($for_customer)
        {
            $entradas = $entradas->where('sent', '==', true)->where('hidden', false);
        }
        
        return $entradas;
    }

    public function get_Incomes_obj_range_dates(array $cliente, string $rango, string $busqueda, string $en_inventario, bool $enviada)
    {
        // '$cliente' en realidad es un array de los customer_id que vamos a filtrar NINGUNO DEBE SER CERO 0
        // lo que se recive en rango 10/08/2021 - 11/12/2021
        $date1 = trim(explode("-",$rango)[0]);
        $date1 = explode("/",$date1);
        $date1 = $date1[2] . "-" . $date1[0] . "-" . $date1[1];

        $date2 = trim(explode("-",$rango)[1]);
        $date2 = explode("/",$date2);
        $date2 = $date2[2] . "-" . $date2[0] . "-" . $date2[1];

        $from = date($date1);
        $to = date($date2);

        $entradas = Income::whereBetween('cdate', [$from, $to])
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
        $entradas = $entradas->orderBy('id', 'desc')->get();

        if($en_inventario != "todo")
        {
            foreach ($entradas as $key => $entrada) 
            {
                $partidas = $entrada->income_rows;
                $count = 0;
                foreach ($partidas as $partida) 
                {
                    if($en_inventario == "Revision Pendiente")
                    {
                        if($partida->part_number()->part_number == "REVISION PENDIENTE")
                        {
                            $count = 1;
                        }
                    }
                    else
                    {
                        $count += ($partida->units - $partida->get_discounted_units());
                    }
                    
                    if($count > 0)
                    {
                        break;
                    }
                }
                if($en_inventario == "Revision Pendiente")
                {
                    if($count == 0)
                    {
                        $entrada->id = 0;
                    }
                }
                if($en_inventario == "en inventario")
                {
                    if($count == 0)
                    {
                        $entrada->id = 0;
                    }
                }
                if($en_inventario == "cerrada")
                {
                    if($count > 0)
                    {
                        $entrada->id = 0;
                    }
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
        $transportistas = Carrier::orderBy('name')->get();
        $proveedores = Supplier::orderBy('name')->get();
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
        $entrada->ubicacion = $request->txtUbicacion ?? "";
        
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
            $number = Income::withTrashed()->where('year',$entrada->year)->max('number');
            $entrada->number = (is_null($number)) ? 1 : $number + 1;
            $entrada->sent = false;
        }
        $entrada->save();
        //ESTA FUNCION BUSCA EVITAR QUE 2 ENTRADAS TOMEN EL MISMO NUMERO EN CASO SE HABER SIDO ENVIADAS JUSTO AL MISMO TIEMPO
        $this->validate_income_number($entrada);
        //
        $numero_de_entrada = $entrada->year.str_pad($entrada->number,5,"0",STR_PAD_LEFT);

        return response()->json([
            'numero_de_entrada' => $numero_de_entrada,
            'id_entrada' => $entrada->id,
        ]);
    }

    public function validate_income_number(Income $entrada)
    {
        //esta funcion edita in objeto Income y modificando su numero de entrada por uno correcto, si mas de una entrada poseen el mismo numero de entrada 
        //si la entrada en cuestion no esta duplicada o esta duplicada pero posee el id mas pequeño dentro de la lista de duplicadas, la entrada original conservara su numero de entrada
        sleep(1); // esperamos un segundo
        $dupIncomes = Income::where('year', $entrada->year)->where('number', $entrada->number)->get();
        if(count($dupIncomes) > 1)
        {
            $minId = $dupIncomes[0]->id;
            foreach ($dupIncomes as $dupInc) 
            {
                //buscamos el menor id en la lista de entradas duplicadas.
                if($dupInc->id < $minId)
                {
                    $minId = $dupInc->id;
                }
            }
            // la entrada con el menor id es la que enrealidad se debe quedar con el numero de entrada duplicado
            // si la entrada actual no tiene el menor id, hay que asignarle un nuevo numero de entrada y repetir el proceso
            if($minId < $entrada->id)
            {
                $new_number = Income::withTrashed()->where('year',$entrada->year)->max('number');
                $entrada->number = (is_null($new_number)) ? 1 : $new_number + 1;
                $entrada->save();
                // volvemos a iniciar la validacion, esta funcion es recursiva
                $this->validate_income_number($entrada);
            }
        }        
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
        $transportistas = Carrier::orderBy('name')->get();
        $proveedores = Supplier::orderBy('name')->get();
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
        //registrar en la bitacora
        Bitacora::registrar("Eliminar Entrada",Auth::user()->name . "-Sistema",$income->getIncomeNumber());

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
                //registrar en la bitacora
                Bitacora::registrar("Eliminar Partida de Entrada",Auth::user()->name . "-Sistema",$income->getIncomeNumber() . " - " . $partida->id . " - " . $partida->part_number()->part_number);

                //ya no se borraran las partidas al eliminar la entrada porque las entradas se eliminan por soft delete. las partidas deben seguir presentes
                //$partida->delete();           
            }
        }
        else
        {
            return "Alguna o algunas de las partidas de ésta entrada ya cuentan con salida: " . $lista_de_salidas . "<br>Verifíque con su equipo.";
        }
        
        $income->delete();
        //borramos los archivos
        //ya no se borraran los archivos al eliminar la entrada por soft delete
        //Storage::deleteDirectory('public/entradas/'.$income->getIncomeNumber());
            
    }

    public function hide(Income $income)
    {
        //registrar en la bitacora
        Bitacora::registrar("Ocultar Entrada",Auth::user()->name . "-Sistema",$income->getIncomeNumber());
        $income->hidden = true;
        $income->save();
    }
    public function unhide(Income $income)
    {
        //registrar en la bitacora
        Bitacora::registrar("Revelar Entrada",Auth::user()->name . "-Sistema",$income->getIncomeNumber());
        $income->hidden = false;
        $income->save();
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

        $pdf = PDF::loadView('intern.entradas.pdf_balance', compact('income'))->setPaper('a4', 'landscape');
        return $pdf->download('balance_'.$numero_de_entrada.'.pdf');
    }

    public function get_income_sums(Income $income)
    {
        $peso_neto = $income->getPesoNeto();
        $peso_bruto = $income->getPesoBruto();
        $piezas = $income->getPiezasSum();
        $bultos = $income->getBultosSum();
        return response()->json([
            'peso_neto' => $peso_neto,
            'peso_bruto' => $peso_bruto,
            'piezas' => $piezas,
            'bultos' => $bultos,
        ]);
    }

    public function nueva_pre_entrada()
    {
        $clientes = Customer::orderBy('name')->get();
        $transportistas = Carrier::orderBy('name')->get();
        $proveedores = Supplier::orderBy('name')->get();
        return view('intern.entradas.pre_entrada', [
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'proveedores' => $proveedores,
        ]);
    }

    public function imprimir(Request $request)
    {
        $entrada = new Income;

        $entrada->cdate = $request->txtFecha;
        $entrada->customer_id = $request->txtCliente;
        $entrada->carrier_id = $request->txtTransportista;
        $entrada->supplier_id = $request->txtProveedor;
        $entrada->reference = "";
        $entrada->trailer = "";
        $entrada->seal = "";
        $entrada->observations = $request->txtObservaciones ?? "";
        $entrada->impoExpo =  "";
        $entrada->invoice = "";
        $entrada->tracking = $request->txtTracking ?? "";
        $entrada->po = $request->txtPO ?? "";
        $entrada->ubicacion = $request->txtLocacion ?? "";

        $entrada->user = $request->txtUsuario ?? "";
        $entrada->reviewed = false;
        $entrada->reviewed_by = $request->txtUsuario ?? "";
        $entrada->closed = false;
        $entrada->urgent = false;
        $entrada->onhold = false;
        $entrada->type = "";

        //asignar numero de entrada
        $entrada->year = date("Y");
        $number = Income::withTrashed()->where('year',$entrada->year)->max('number');
        $entrada->number = (is_null($number)) ? 1 : $number + 1;
        $entrada->sent = false;

        $entrada->save();
        $numero_de_entrada = $entrada->year.str_pad($entrada->number,5,"0",STR_PAD_LEFT);

        //subir packinglist
        if($_FILES['file']['name'] != "") 
        {
            Storage::put('/public/entradas/'.$numero_de_entrada.'/packing_list/packing-list.pdf', file_get_contents($request->file('file')));
        }
        //subir imagenes
        if(null !== $request->file('imagenes'))
        {
            $i = 0;
            foreach ($request->file('imagenes') as $file) 
            {
                $i++;
                Storage::put('/public/entradas/'.$numero_de_entrada.'/images/'.time().'_'.$i.'.'.$file->extension(), file_get_contents($file), 'public');
            }
        }
        

        //agregar partida de REVISION PENDIENTE
        $incomeRow = new IncomeRow;
        $incomeRow->income_id = $entrada->id;

        //buscamos el numero de parte "REVISION PENDIENTE" si no lo tiene hay que crearlo

        $part_number = PartNumber::where('part_number','REVISION PENDIENTE')->first();

        if(!$part_number)
        {
            $part_number = new PartNumber;
            $part_number->part_number = 'REVISION PENDIENTE';
            $part_number->customer_id = $entrada->customer_id;
            $part_number->um = 'Pieza';
            $part_number->unit_weight = 1.0;
            $part_number->desc_ing = 'REVISION PENDIENTE';
            $part_number->desc_esp = 'REVISION PENDIENTE';
            $part_number->origin_country = 'MX';
            $part_number->fraccion = '99999999';
            $part_number->nico = '99';
            $part_number->brand = '';
            $part_number->model = '';
            $part_number->serial = '';
            $part_number->imex = '';
            $part_number->fraccion_especial = '';
            $part_number->regime = '';
            $part_number->warning = '';
            $part_number->save();
        }

        $incomeRow->part_number_id = $part_number->id ;
        $incomeRow->units = 1 ;
        $incomeRow->bundles = $request->txtBultos ;
        $incomeRow->umb = "Atados" ;
        $incomeRow->ump = "Pieza" ;
        $incomeRow->net_weight = 1.0 ;
        $incomeRow->gross_weight = 1.0 ;
        $incomeRow->po = $request->txtPO ?? "";
        $incomeRow->desc_ing = "";
        $incomeRow->desc_esp = "";
        $incomeRow->origin_country = $part_number->origin_country;
        $incomeRow->fraccion = $part_number->fraccion ;
        $incomeRow->nico = $part_number->nico;
        $incomeRow->location = $request->txtLocacion ?? "";
        $incomeRow->observations = "";
        $incomeRow->brand =  "";
        $incomeRow->model = "";
        $incomeRow->serial = "";
        $incomeRow->lot = "";
        $incomeRow->imex = "";
        $incomeRow->regime = "";
        $incomeRow->skids = "";

        $incomeRow->save();

        if(!is_null($incomeRow->id))
        {
            //registrar bultos en inventario
            $inv_bundle = new InventoryBundle;
            $inv_bundle->income_row_id = $incomeRow->id;
            $inv_bundle->quantity = $incomeRow->bundles;
            $inv_bundle->save();
        }
        else
        {
            return "La partida no se pudo guardar, verifique los datos.";
        }

        //Imprimir etiquetas
        // 1 inch = 72 point

        
        // //dimensiones:
        // //  1.25 x 3.5 pugadas
        // //  90   x 252 points
        // $customPaper = array(0,0,90.00,252.00);
        // $pdf = PDF::loadView('intern.entradas.etiquetas', compact('entrada'))->setPaper($customPaper, 'landscape');

        
        return $pdf->stream();
    }

    public function imprimir_etiquetas(string $incomeid)
    {
        $entrada =  Income::find($incomeid);
        //Imprimir etiquetas
        // 1 inch = 72 point

        // //dimensiones:
        // //  1.25 x 3.5 pugadas
        // //  90   x 252 points
        // $customPaper = array(0,0,90.00,252.00);
        // $pdf = PDF::loadView('intern.entradas.etiquetas', compact('entrada'))->setPaper($customPaper, 'landscape');

        //dimensiones:
        //  2.25 x 4 pugadas
        //  162   x 288 points
        $customPaper = array(0,0,162.00,288.00);
        $pdf = PDF::loadView('intern.entradas.etiquetas2x4', compact('entrada'))->setPaper($customPaper, 'landscape');


        return $pdf->stream();
    }

    

    
}
