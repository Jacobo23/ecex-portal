<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LoadorderExport implements FromView
{
    protected $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('intern.ordenes_de_carga.tbl_xls_OC', [
            'oc_rows' => $this->data
        ]);
    }
}