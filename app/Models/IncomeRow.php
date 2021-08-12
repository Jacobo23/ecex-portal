<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PartNumber;

class IncomeRow extends Model
{
    use HasFactory;

    public function part_number()
    {
        return PartNumber::find($this->part_number_id);
    }
}
