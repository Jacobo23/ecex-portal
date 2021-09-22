<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LoadOrderRow;
use App\Models\Customer;

class LoadOrder extends Model
{
    use HasFactory;

    public function load_order_rows()
    {
        return $this->hasMany(LoadOrderRow::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function get_peso_neto()
    {
        $load_order_rows = $this->load_order_rows;
        $peso_neto = 0;
        foreach ($load_order_rows as $load_order_row) 
        {
            $income_row = $load_order_row->income_row;
            $part_number_unit_weight = $income_row->part_number()->unit_weight;
            $units = $load_order_row->units;
            $peso_neto += $part_number_unit_weight * $units;
        }
        return $peso_neto;
    }
}
