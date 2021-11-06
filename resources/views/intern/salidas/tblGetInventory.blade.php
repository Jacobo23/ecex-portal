@if (isset($inventario_suficiente))
    @if (!$inventario_suficiente)
        <div id="fraccionAlert" class="alert alert-warning" role="alert">
            Algunas partidas ya no cuentan con el inventario señalado en la OC. Se mostrará el valor restante en esas partidas.
        </div>
    @endif
@endif
<table class="table table-sm table-bordered table-hover">
    <thead>
        <tr>
            <th scope="col" style="text-align:center"><i class="fas fa-check-square"></i></th>
            <th scope="col" style="text-align:center">Entrada #</th>
            <th scope="col" style="text-align:center">Fecha</th>
            <th scope="col" style="text-align:center">Número_de_parte</th>
            <th scope="col" style="text-align:center">Piezas</th>
            <th scope="col" style="text-align:center">Bultos</th>
            <th scope="col" style="text-align:center">Tipo_bulto</th>
            <th scope="col" style="text-align:center">Peso_neto</th>
            <th scope="col" style="text-align:center">Peso_bruto</th>
            <th scope="col" style="text-align:center; min-width:200px;">Description</th>
            <th scope="col" style="text-align:center">PO</th>
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
        <tr id="inv_row_{{ $row->id }}_{{ $loop->index }}" class="{{ $tr_color }}">
            <td>
                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <input type="checkbox" class="chkSingle income_{{ $row->income_id }} btn-check" id="btncheck_{{ $row->id }}_{{ $loop->index }}" autocomplete="off" onchange="selectRow('{{ $row->id }}','{{ $loop->index }}')">
                    <label class="btn btn-outline-success btn-sm" for="btncheck_{{ $row->id }}_{{ $loop->index }}"><i class="far fa-check-square"></i></label>
                    @if ($is_first)
                    <input type="checkbox" class="chkGroup btn-check" id="btncheckAll_{{ $row->id }}_{{ $row->income_id }}" autocomplete="off" onchange="selectGroup(this,'{{ $row->income_id }}')">
                    <label class="btn btn-outline-primary btn-sm" for="btncheckAll_{{ $row->id }}_{{ $row->income_id }}"><i class="fas fa-tasks"></i></label>
                    @endif
                </div>
            </td>
            <td>{{ $row->income->getIncomeNumber() }}<input type="hidden" id="txtIncomeRowId_{{ $row->id }}_{{ $loop->index }}" name="income_row_id[]" value="{{ $row->id }}"></td>
            <td class="oversized">{{ explode(' ',$row->income->cdate)[0] }}</td>
            <td>{{ $row->part_number()->part_number }} <input type="hidden" name="txtNumeroDePartePesoU_{{ $row->id }}_{{ $loop->index }}" id="txtNumeroDePartePesoU_{{ $row->id }}_{{ $loop->index }}" value="{{ $row->part_number()->unit_weight }}"></td>
            <td class="oversized">
                <input type="number" class="form-control" id="txtCantidad_{{ $row->id }}_{{ $loop->index }}" name="units[]" value="{{ $row->units }}" max="{{ $row->units }}" min="0" onchange="validarCantidad(this,{{ $row->units }},'{{ $loop->index }}')" readonly>
                <!-- {{ $row->ump }} -->
                <input type="hidden" id="txtUM_{{ $row->id }}_{{ $loop->index }}" name="ump[]" value="{{ $row->ump }}">
            </td>
            <td><input type="number" class="form-control" id="txtBultos_{{ $row->id }}_{{ $loop->index }}" name="bundles[]" value="{{ $row->bundles }}" min="0" readonly onchange="calcularPesoBruto({{ $row->id }},'{{ $loop->index }}')"></td>
            <td>
                <select class="form-select" id = "txtUMB_{{ $row->id }}_{{ $loop->index }}" name = "umb[]" disabled onchange="tipoBultoChange({{ $row->id }},'{{ $loop->index }}')">
                    @php 
                    $peso_bulto = 0;
                    foreach ($tipos_de_bulto as $tipos_de_bultoOp)
                    {
                        $selected = "";
                        if($row->umb === $tipos_de_bultoOp->desc)
                        {
                            $selected = "selected";
                            $peso_bulto = $tipos_de_bultoOp->weight;
                        }
                        echo "<option value='".$tipos_de_bultoOp->desc."' ".$selected." >".$tipos_de_bultoOp->desc."</option>";
                    }
                    @endphp
                </select>
                @php
                echo "<input type='hidden' name='txtUMBPeso_".$row->id."' id='txtUMBPeso_".$row->id."_".$loop->index."' value='".$peso_bulto."'>";
                @endphp
            </td>
            <td><input type="number" class="form-control" id="txtPesoNeto_{{ $row->id }}_{{ $loop->index }}" min="0" name="net_weight[]" value="{{ $row->part_number()->unit_weight * $row->units }}" readonly  onchange="calcularPesoBruto({{ $row->id }},'{{ $loop->index }}')"></td>
            <td><input type="number" class="form-control" id="txtPesoBruto_{{ $row->id }}_{{ $loop->index }}" min="0" name="gross_weight[]" value="{{ $row->gross_weight }}" readonly></td>
            <td style="font-size: 0.9em;">{{ $row->desc_ing }}</td>
            <td>{{ $row->po }}</td>
        </tr>
        @endforeach
    </tbody>
</table>





