<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OutcomeRow;
use App\Models\Customer;

class Outcome extends Model
{
    use HasFactory;
    public function outcome_rows()
    {
        return $this->hasMany(OutcomeRow::class);
    }
    public function getOutcomeNumber()
    {
        return $this->year.str_pad($this->number,5,"0",STR_PAD_LEFT)."-".$this->regime;
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
}
