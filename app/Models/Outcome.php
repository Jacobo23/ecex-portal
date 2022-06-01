<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OutcomeRow;
use App\Models\Customer;
use App\Models\Carrier;
use App\Models\ConversionFactor;


class Outcome extends Model
{
    use HasFactory;
    public function outcome_rows()
    {
        return $this->hasMany(OutcomeRow::class);
    }

    public function getIncomes()
    {
        $rows = $this->outcome_rows;
        $incomes = array();
        foreach ($rows as $row)
        {
            $income_aux = $row->income_row->income->getIncomeNumber();
            array_push($incomes,$income_aux);
        }
        $incomes=array_unique($incomes);
        $uniques = array();
        foreach ($incomes as $income) 
        {
            if($income)
            {
                array_push($uniques,$income);
            }
        }
        return $uniques;
    }

    public function getOutcomeNumber($regime)
    {
        $posfix = "";
        if($regime)
        {
            $posfix = "-".$this->regime;
        }
        return $this->year.str_pad($this->number,5,"0",STR_PAD_LEFT).$posfix;
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }


    public function getBultos()
    {
        $rows = $this->outcome_rows;
        $count = 0;
        foreach ($rows as $row)
        {
            $count += $row->bundles;
        }
        return $count;
    }
    public function getTipoBultos()
    {
        $rows = $this->outcome_rows;
        $umb="";
        $i=0;
        foreach ($rows as $row)
        {
            if ($i == 0)
            {
                $umb=$row->umb;
            }
            else
            {
                if ($umb != $row->umb)
                {
                    return "VARIOS";
                }
            }
            $i++;
        }
        return $umb;
    }

    public function getPesoNeto()
    {
        $rows = $this->outcome_rows;
        $count = 0;
        foreach ($rows as $row)
        {
            $count += $row->net_weight;
        }
        return $count;
    }

    public function getPesoBruto()
    {
        $rows = $this->outcome_rows;
        $count = 0;
        foreach ($rows as $row)
        {
            $count += $row->gross_weight;
        }
        return $count;
    }
    public function getPiezasSum()
    {
        $piezas_sum = OutcomeRow::where('outcome_id',$this->id)
            ->selectRaw("SUM(units) as sum, ump")
            ->groupBy("ump")
            ->get();
        $res = "";
        foreach ($piezas_sum as $row) 
        {            
            $sum = ($row["sum"] * 1);
            $res .= $sum . " " . $row["ump"] . ($row["sum"] > 1 ? "(s)" : "") . " / " .
                    $this->convert_unit($row["ump"],$sum) . " " . $this->converting_unit($row["ump"]) . "<br>";
        }
        return $res;
    }
    public function getPiezasTotalSum()
    {
        $piezas_sum = OutcomeRow::where('outcome_id',$this->id)
            ->selectRaw("SUM(units) as sum")
            ->get();
        $res = "";
        foreach ($piezas_sum as $row) 
        {
            $res .= ($row["sum"] * 1) . "<br>";
        }
        return $res;
    }

    public function getBultosSum()
    {
        $piezas_sum = OutcomeRow::where('outcome_id',$this->id)
            ->selectRaw("SUM(bundles) as sum, umb")
            ->groupBy("umb")
            ->get();
        $res = "";
        foreach ($piezas_sum as $row) 
        {
            $res .= ($row["sum"] * 1) . " " . $row["umb"] . "<br>";
        }
        return $res;
    }
    public function getBultosTotalSum()
    {
        $piezas_sum = OutcomeRow::where('outcome_id',$this->id)
            ->selectRaw("SUM(bundles) as sum")
            ->get();
        $res = "";
        foreach ($piezas_sum as $row) 
        {
            $res .= ($row["sum"] * 1) .  "<br>";
        }
        return $res;
    }
    // si no hay unidad de conversion regresa un texto vacio
    public function converting_unit($ump)
    {
        $converting_unit = ConversionFactor::Where('desc',$ump)->first();
        if(!$converting_unit)
        {
            return "";
        }
        return $converting_unit->convert2;
    }
    // si no hay unidad de conversion regresa un texto vacio
    public function convert_unit($ump, $units)
    {
        $converting_unit = ConversionFactor::Where('desc',$ump)->first();
        if(!$converting_unit)
        {
            return "";
        }
        return round($units * $converting_unit->factor,2);
    }
}
