<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomeRow;
use App\Models\PartNumber;


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
}
