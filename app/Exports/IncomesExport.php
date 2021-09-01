<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class IncomesExport implements FromView
{
    protected $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('intern.entradas.tbl_xsl_incomes', [
            'incomes' => $this->data
        ]);
    }
}