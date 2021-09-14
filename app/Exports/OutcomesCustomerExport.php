<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OutcomesCustomerExport implements FromView
{
    protected $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('customer.salidas.tbl_xsl_outcomes', [
            'outcomes' => $this->data
        ]);
    }
}