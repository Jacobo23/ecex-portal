<html>

<head>
<style>
.th
{
    background-color: #000000;
    color: #ffffff;
}
</style>
</head>


<table>
    <thead>
        <tr>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Salida</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Fecha</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Cliente</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Transportista</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Caja</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Placas</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Sello</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Factura</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Pedimento</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Referencia</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Recibido por</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Observaciones</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Usuario</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Enviada</th>
            
            <th style="background-color: #fcba03; text-align:center; font-weight:bold;">Entrada</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold;">Fecha entrada</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold;">Número de parte</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold;">Descripcion Ing</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold;">Descripcion Esp</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold">Cantidad de piezas</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold">UM. Piezas</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold">Bultos</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold">UM. Bulto</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold">Peso neto</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold">Peso bruto</th>
            <th style="background-color: #fcba03; text-align:center; font-weight:bold">Locacion</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($outcomes as $outcome)
        @foreach ($outcome->outcome_rows as $outcome_row)
        <tr>
            <td>{{ $outcome->getOutcomeNumber($outcome->regime) }}</td>
            <td>{{ explode(" ", $outcome->cdate)[0] }}</td>
            <td>{{ $outcome->customer->name }}</td>
            <td>{{ $outcome->carrier->name }}</td>
            <td>{{ $outcome->trailer }}</td>
            <td>{{ $outcome->plate }}</td>
            <td>{{ $outcome->seal }}</td>
            <td>{{ $outcome->invoice }}</td>
            <td>{{ $outcome->pediment }}</td>
            <td>{{ $outcome->reference }}</td>
            <td>{{ $outcome->received_by }}</td>
            <td>{{ $outcome->observations }}</td>
            <td>{{ $outcome->user }}</td>
            <td>@if($outcome->sent) Yes @endif</td>
            <td>{{ $outcome_row->income_row->income->getIncomeNumber() }}</td>
            <td>{{ explode(" ", $outcome_row->income_row->income->cdate)[0] }}</td>
            <td>{{ $outcome_row->income_row->part_number()->part_number }}</td>
            <td>{{ $outcome_row->income_row->desc_ing }}</td>
            <td>{{ $outcome_row->income_row->desc_esp }}</td>
            <td>{{ $outcome_row->units }}</td>
            <td>{{ $outcome_row->ump }}</td>
            <td>{{ $outcome_row->bundles }}</td>
            <td>{{ $outcome_row->umb }}</td>
            <td>{{ $outcome_row->net_weight }}</td>
            <td>{{ $outcome_row->gross_weight }}</td>
            <td>{{ $outcome_row->income_row->location }}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>
</html>