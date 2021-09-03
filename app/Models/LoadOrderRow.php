<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomeRow;

class LoadOrderRow extends Model
{
    use HasFactory;

    public function income_row()
    {
        return $this->belongsTo(IncomeRow::class);
    }
}
