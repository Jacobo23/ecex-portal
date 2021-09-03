<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class IncomesCustomerExport implements FromView
{
    protected $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('customer.entradas.tbl_xsl_incomes', [
            'incomes' => $this->data
        ]);
    }
}