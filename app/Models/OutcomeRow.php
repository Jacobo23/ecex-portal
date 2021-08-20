<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Outcome;
use App\Models\IncomeRow;

class OutcomeRow extends Model
{
    use HasFactory;

    public function outcome()
    {
        return $this->belongsTo(Outcome::class);
    }
    public function income_row()
    {
        return $this->belongsTo(IncomeRow::class);
    }
}
