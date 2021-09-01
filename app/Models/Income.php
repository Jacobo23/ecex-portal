<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomeRow;
use App\Models\Customer;
use App\Models\Carrier;
use App\Models\Supplier;

class Income extends Model
{
    use HasFactory;
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

        return strval($diff->format("%a")) -  $fines_de_semana;
    }
    public function getBultos()
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


    
}


