<table class="table table-sm table-bordered table-hover">
    <thead>
        <tr>
            <th scope="col" style="text-align:center"><i class="fas fa-check-square"></i></th>
            <th scope="col" style="text-align:center">Entrada #</th>
            <th scope="col" style="text-align:center">Fecha</th>
            <th scope="col" style="text-align:center">Número_de_parte</th>
            <th scope="col" style="text-align:center">Piezas</th>
            <th scope="col" style="text-align:center">Um.</th>
            <th scope="col" style="text-align:center">Bultos</th>
            <th scope="col" style="text-align:center">Peso_neto</th>
            <th scope="col" style="text-align:center; min-width:200px;">Description</th>
        </tr>
    </thead>
    <tbody>
        @php 
        $aux_income = "";
        $tr_color = "table-secondary";
        
        @endphp
        @foreach ($inventory as $row)
        @php 
            $is_first = false;
            if($aux_income != $row->income_id)
            {
                $aux_income = $row->income_id;
                $is_first = true;
                if ($tr_color == "")
                {
                    $tr_color = "table-secondary";
                }
                else
                {
                    $tr_color = "";
                }
            }
        @endphp
        <tr id="inv_row_{{ $row->id }}" class="{{ $tr_color }}">
            <td>
                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <input type="checkbox" class="chkSingle income_{{ $row->income_id }} btn-check" id="btncheck_{{ $row->id }}" autocomplete="off" onchange="selectRow('{{ $row->id }}')">
                    <label class="btn btn-outline-success btn-sm" for="btncheck_{{ $row->id }}"><i class="far fa-check-square"></i></label>
                    @if ($is_first)
                    <input type="checkbox" class="chkGroup btn-check" id="btncheckAll_{{ $row->id }}_{{ $row->income_id }}" autocomplete="off" onchange="selectGroup(this,'{{ $row->income_id }}')">
                    <label class="btn btn-outline-primary btn-sm" for="btncheckAll_{{ $row->id }}_{{ $row->income_id }}"><i class="fas fa-tasks"></i></label>
                    @endif
                </div>
            </td>
            <td><label id="lblIncome_{{ $row->id }}">{{ $row->income->getIncomeNumber() }}</label><input type="hidden" id="txtIncomeRowId_{{ $row->id }}" name="income_row_id[]" value="{{ $row->id }}"></td>
            <td>{{ explode(' ',$row->income->cdate)[0] }}</td>
            <td id="tdPN_{{ $row->id }}">{{ $row->part_number()->part_number }}</td>
            <td>
                <input type="number" class="form-control" style="width:150px" id="txtCantidad_{{ $row->id }}" name="units[]" value="{{ $row->units }}" min="0" max="{{ $row->units }}" onchange="validarCantidad(this,{{ $row->units }})" readonly>
            </td>
            <td>{{ $row->ump }}</td>
            <td>{{ $row->bundles }} {{ $row->umb }}</td>
            <td>{{ $row->part_number()->unit_weight * $row->units }}</td>
            <td style="font-size: 0.9em;">{{ $row->desc_ing }}</td>
        </tr>
        @endforeach
    </tbody>
</table>








