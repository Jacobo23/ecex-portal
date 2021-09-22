<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PartNumber;
use App\Models\Outcome;
use App\Models\OutcomeRow;
use App\Models\Income;
use App\Models\InventoryBundle;


class IncomeRow extends Model
{
    use HasFactory;

    public function part_number()
    {
        return PartNumber::find($this->part_number_id);
    }
    public function income()
    {
        //regresa la entrada a la que pertenece esta partida
        return $this->belongsTo(Income::class);
    }
    public function get_discounted_units()
    {
        //esta funcion regresa un numero de las unitades que se han descontado a la income_row
        $descuentos = OutcomeRow::where('outcome_rows.income_row_id', '=', $this->id)
            ->join('outcomes', 'outcomes.id', '=', 'outcome_rows.outcome_id')
            ->where('outcomes.discount',true)
            ->sum('outcome_rows.units');
        return $descuentos;
    }
    public function get_discounting_outcomes()
    {
        //esta funcion regresa las salidas que descuentan a esta income_row
        $outcome_ids = OutcomeRow::where('outcome_rows.income_row_id', '=', $this->id)->pluck('outcome_id');
        $outcomes = Outcome::whereIn('id',$outcome_ids)->get();
        $outcome_numbers=array();
        foreach ($outcomes as $outcome) 
        {
            array_push($outcome_numbers,$outcome->getOutcomeNumber(true));
        }
        return $outcome_numbers;
    }
    
}
