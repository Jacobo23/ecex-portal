<?php

namespace App\Http\Controllers;
use App\Models\OutcomeRow;
use App\Models\IncomeRow;
use App\Models\Income;
use App\Models\BundleType;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // este controlador no tiene tabla en la base de datos ni modelo, sus metodos y funciones deben llamarse atravez de este documento
    public function get(string $customer, string $days_range)
    {
        //$available_rows = IncomeRow::whereDate('income_rows.created_at', '<=', now()->subDays($days_range)->setTime(0, 0, 0)->toDateTimeString())
        //    ->join('incomes', 'incomes.id', '=', 'income_rows.income_id')
        //    ->select('income_rows.*', 'incomes.customer_id')
        //    ->get();
//
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
}
