<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomeRow;
use App\Models\PartNumber;
use App\Models\ConversionFactor;



class LoadOrderRow extends Model
{
    use HasFactory;

    public function income_row()
    {
        return $this->belongsTo(IncomeRow::class);
    }
    public function get_peso_neto()
    {
        $peso_unitario = $this->income_row->part_number()->unit_weight;
        return $peso_unitario * $this->units;
    }
    // si no hay unidad de conversion regresa un texto vacio
    public function converting_unit()
    {
        $converting_unit = ConversionFactor::Where('desc',$this->income_row->ump)->first();
        if(!$converting_unit)
        {
            return "";
        }
        return $converting_unit->convert2;
    }
    // si no hay unidad de conversion regresa un texto vacio
    public function convert_unit()
    {
        $converting_unit = ConversionFactor::Where('desc',$this->income_row->ump)->first();
        if(!$converting_unit)
        {
            return "";
        }
        return round($this->units * $converting_unit->factor,2);
    }
}
