<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OutcomeRow;
use App\Models\Customer;
use App\Models\Carrier;

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
            $res .= ($row["sum"] * 1) . " " . $row["ump"] . ($row["sum"] > 1 ? "(s)" : "") . "<br>";
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
}
