<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomeRow;

class Income extends Model
{
    use HasFactory;

    public function income_rows()
    {
        return $this->hasMany(IncomeRow::class);
    }
}


