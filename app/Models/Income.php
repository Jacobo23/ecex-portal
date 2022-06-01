<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomeRow;
use App\Models\Customer;
use App\Models\Carrier;
use App\Models\Supplier;
use App\Models\InventoryBundle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ConversionFactor;


class Income extends Model
{
    use HasFactory;
    use SoftDeletes;
    //relaciones
    public function income_rows()
    {
        return $this->hasMany(IncomeRow::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    //funciones
    public function getIncomeNumber()
    {
        return $this->year.str_pad($this->number,5,"0",STR_PAD_LEFT);
    }
    public function getDiasTrascurridos()
    {
        $date1=date_create($this->cdate);
        $date2=date_create(date("Y-m-d"));
        $diff=date_diff($date1,$date2);

        $iter = 24*60*60; // segundos de dia completo
        $fines_de_semana = 0;
    
        for($i = 0; $date1 <= $date2; $i++)
        {
            date_add($date1,date_interval_create_from_date_string("1 days"));
            $weekday = date_format($date1,"D");
            if($weekday == 'Sat' || $weekday == 'Sun')
            {
                $fines_de_semana++;
            }
        }

        $dias = strval($diff->format("%a")) -  $fines_de_semana;
        if($dias < 0)
        {
            $dias = 0;
        }

        return $dias;
    }
    public function getBultos()
    {
        $rows = $this->income_rows;
        $count = 0;
        foreach ($rows as $row)
        {
            $inv_bundle = InventoryBundle::where('income_row_id',$row->id)->first();
            if($inv_bundle === null)
            {
                $inv_bundle = new InventoryBundle;
                $inv_bundle->income_row_id = $row->id;
                $inv_bundle->quantity = $row->bundles;
                $inv_bundle->save();
            }
            //
            $count += $inv_bundle->quantity;
        }
        return $count;
    }
    public function getBultosOriginales()
    {
        $rows = $this->income_rows;
        $count = 0;
        foreach ($rows as $row)
        {
            $count += $row->bundles;
        }
        return $count;
    }
    public function getTipoBultos()
    {
        $rows = $this->income_rows;
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
    public function getCantidadPartidas()
    {
        return count($this->income_rows);
    }
    public function getDate()
    {
        $date = explode(" ", $this->cdate)[0];
        $year = explode("-", $date)[0];
        $month = explode("-", $date)[1];
        $day = explode("-", $date)[2];
        return $month . "/" . $day . "/" . $year;
    }
    public function getPesoNeto()
    {
        $rows = $this->income_rows;
        $count = 0;
        foreach ($rows as $row)
        {
            $count += $row->net_weight;
        }
        return $count;
    }
    public function getPesoBruto()
    {
        $rows = $this->income_rows;
        $count = 0;
        foreach ($rows as $row)
        {
            $count += $row->gross_weight;
        }
        return $count;
    }
    public function get_color_fila_estado()
    {
        //en la lista de entradas pintaremos usando clases de bootstrap dependiendo del estado de la entrada
        $color = "";
        $limite = 4;//dias
        if($this->getDiasTrascurridos() >= $limite)
        {
            if($this->urgent)
            {
                $color = "danger";
            }
            else
            {
                $color = "warning";
            }
        }
        if($this->onhold)
        {
            $color = "secondary";
        }
        return $color;
    }

    public function getPiezasSum()
    {
        $piezas_sum = IncomeRow::where('income_id',$this->id)
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
        $piezas_sum = IncomeRow::where('income_id',$this->id)
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
        $piezas_sum = IncomeRow::where('income_id',$this->id)
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
        $piezas_sum = IncomeRow::where('income_id',$this->id)
            ->selectRaw("SUM(bundles) as sum")
            ->get();
        $res = "";
        foreach ($piezas_sum as $row) 
        {
            $res .= ($row["sum"] * 1) . "<br>";
        }
        return $res;
    }
    public function getBultosSumFromInv()
    {
        $bultos_sum = IncomeRow::where('income_id',$this->id)
            ->leftJoin('inventory_bundles', 'income_rows.id', '=', 'inventory_bundles.income_row_id')
            ->selectRaw("SUM(inventory_bundles.quantity) as sum, income_rows.umb")
            ->groupBy("umb")
            ->get();
        $res = "";
        foreach ($bultos_sum as $row) 
        {
            $res .= ($row["sum"] * 1) . " " . $row["umb"] . "<br>";
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