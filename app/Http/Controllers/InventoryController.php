<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\OutcomeRow;
use App\Models\IncomeRow;
use App\Models\Income;
use App\Models\InventoryBundle;
use App\Models\BundleType;
use App\Models\PartNumber;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryExport;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // este controlador no tiene tabla en la base de datos ni modelo, sus metodos y funciones deben llamarse atravez de este documento
    
    //funcion get() se llama cuando necesitas el inventario de un cliente en especifico, optimizado para consulta desde outcome_rows para mostrar las partidas seleccionables
    public function get(string $customer, string $days_range)
    {
        $incomes_ids = Income::where('customer_id', $customer)->where([
            ['customer_id', '=', $customer],
            ['cdate', '>=', now()->subDays($days_range)->setTime(0, 0, 0)->toDateTimeString()],
        ])->pluck('id');

        $available_rows = IncomeRow::whereIn('income_rows.income_id', $incomes_ids )->get();
        //obtener salidas para las income_rows seleccionadas
        foreach ($available_rows as $available_row) 
        {
            $descuentos = $available_row->get_discounted_units();
            $available_row->units -= $descuentos;
        }
        //remover las que queden en cero units
        $available_rows = $available_rows->where('units', '>', 0);

        $umb = BundleType::All();
        return view('intern.salidas.tblGetInventory', [
            'inventory' => $available_rows,
            'tipos_de_bulto' => $umb,
        ]);
    }
    public function get_for_oc(string $days_range)
    {
        $customer = explode(",",Auth::user()->customer_ids)[0];//solo se puede para un cliente a la vez

        $incomes_ids = Income::where('customer_id', $customer)->where('cdate', '>=', now()->subDays($days_range)->setTime(0, 0, 0)->toDateTimeString())->pluck('id');

        $available_rows = IncomeRow::whereIn('income_rows.income_id', $incomes_ids )->get();
        //obtener salidas para las income_rows seleccionadas
        foreach ($available_rows as $available_row) 
        {
            $descuentos = $available_row->get_discounted_units();
            $available_row->units -= $descuentos;
        }
        //remover las que queden en cero units
        $available_rows = $available_rows->where('units', '>', 0);

        return view('intern.salidas.tblGetInventoryOC', [
            'inventory' => $available_rows,
        ]);
    }

    // funcion getAll() para consulta de inventario en modulo de inventario, regresa un objeto que se debe procesar ya sea por una view o para volcar en un xlsx
    //una implementacion similar se encuentra en: IncomeController@index, si debe hacer un cambio a esta funcion posiblemente considere hacerla en la funcion mencionada
    public function getAll(array $cliente,  string $rango, string $others)
    {
        $others = ($others == "NO_FILTER") ? "" : $others;

        $incomes_ids = Income::whereDate('cdate', '>=', now()->subDays(intval($rango))->setTime(0, 0, 0)->toDateTimeString())
            ->where('tracking', 'like', '%'.$others.'%');
        if(count($cliente) > 0)
        {
            $incomes_ids = $incomes_ids->whereIn('customer_id',$cliente);
        }
        $incomes_ids = $incomes_ids->pluck('id');

        //ya tenemos la lista de entradas que se van a consultar
        //ahora debemos buscar sus partidas descontando sus salidas

        $available_rows = IncomeRow::whereIn('income_rows.income_id', $incomes_ids )->get();
        //obtener salidas para las income_rows seleccionadas
        foreach ($available_rows as $available_row) 
        {
            $descuentos = $available_row->get_discounted_units();
            $available_row->units -= $descuentos;
            // calculamos el peso neto tomando en cuenta los descuentos
            $row_part_number = $available_row->part_number();
            $available_row->net_weight = $available_row->units * $row_part_number->unit_weight;
            $available_row->income; // <- invocamos esta propiedad para que el objeto final cuente con informacion de su entrada
        }
        //remover las que queden en cero units
        $available_rows = $available_rows->where('units', '>', 0);
        
        return $available_rows;
    }
    public function index(Request $request)
    {
        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 30;
        $otros = $request->txtOtros ?? "";//no se esta utilizando

        $clientes = Customer::All();

        $data = $this->getAll($cliente_ids, $rango, "NO_FILTER");

        return view('intern.inventario.index', [
            'partidas' => $data,
            'clientes' => $clientes,
            'cliente' => $cliente,
            'rango' => $rango,
            'otros' => $otros,
        ]);

    }
    public function index_customer(Request $request)
    {
        $cliente = explode(",",Auth::user()->customer_ids);
        $rango = $request->txtRango ?? 30;
        $otros = $request->txtOtros ?? "";//no se esta utilizando

        $data = $this->getAll($cliente, $rango, "NO_FILTER");

        return view('customer.inventario.index', [
            'partidas' => $data,
            'cliente' => $cliente,
            'rango' => $rango,
            'otros' => $otros,
        ]);

    }
    public function downloadInventory(Request $request)
    {
        $cliente = $request->txtCliente ?? 0;
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 30;
        $otros = $request->txtOtros ?? "";

        $partidas = $this->getAll($cliente_ids, $rango, "NO_FILTER");
        $export = new InventoryExport($partidas);
        return Excel::download($export, 'inventory.xlsx');
    }
    public function downloadInventory_customer(Request $request)
    {
        $cliente = explode(",",Auth::user()->customer_ids);
        $cliente_ids = ($cliente == 0) ? $cliente = array() : array($cliente);
        $rango = $request->txtRango ?? 30;
        $otros = $request->txtOtros ?? "";

        $partidas = $this->getAll($cliente, $rango, "NO_FILTER");
        $export = new InventoryExport($partidas);//usamos el mismo que para los empleados porque los campos no cambian mucho
        return Excel::download($export, 'inventory.xlsx');
    }

}
