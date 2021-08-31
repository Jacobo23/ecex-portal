<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\IncomeRow;

class PartNumber extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function dependent_Incomes()
    {
        $dependent_income_rows = IncomeRow::where('part_number_id',$this->id)->get();
        $dependent_incomes="";

        foreach ($dependent_income_rows as $dependent_income_row)
        {
            $dependent_incomes .= " " . $dependent_income_row->income->getIncomeNumber() . " ";
        }
        return $dependent_incomes; 
    }
}
