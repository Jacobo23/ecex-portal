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
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Entrada</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Fecha</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Dias</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Cliente</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Impo/Expo</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">on-hold</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Materia/Equipo</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Transportista</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Referencia</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Caja</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Sello</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Proveedor</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Factura</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Tracking</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">PO</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Usuario</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Revisada</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Revisada por</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Enviada</th>
            <th style="background-color: #fcba03; font-weight:bold">Número de parte</th>
            <th style="background-color: #fcba03; font-weight:bold">Cantidad de piezas</th>
            <th style="background-color: #fcba03; font-weight:bold">UM. Piezas</th>
            <th style="background-color: #fcba03; font-weight:bold">Bultos</th>
            <th style="background-color: #fcba03; font-weight:bold">UM. Bulto</th>
            <th style="background-color: #fcba03; font-weight:bold">Peso neto</th>
            <th style="background-color: #fcba03; font-weight:bold">Peso bruto</th>
            <th style="background-color: #fcba03; font-weight:bold">PO</th>
            <th style="background-color: #fcba03; font-weight:bold">Descripcion Ing</th>
            <th style="background-color: #fcba03; font-weight:bold">Descripcion Esp</th>
            <th style="background-color: #fcba03; font-weight:bold">Pais</th>
            <th style="background-color: #fcba03; font-weight:bold">Fraccion</th>
            <th style="background-color: #fcba03; font-weight:bold">Marca</th>
            <th style="background-color: #fcba03; font-weight:bold">Modelo</th>
            <th style="background-color: #fcba03; font-weight:bold">Serie</th>
            <th style="background-color: #fcba03; font-weight:bold">Locacion</th>
            <th style="background-color: #fcba03; font-weight:bold">Skid</th>
            <th style="background-color: #fcba03; font-weight:bold">IMMEX</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($incomes as $income)
        @foreach ($income->income_rows as $income_row)
        <tr>
            <td>{{ $income->getIncomeNumber() }}</td>
            <td>{{ $income->getDate() }}</td>
            <td>{{ $income->getDiasTrascurridos() }}</td>
            <td>{{ $income->customer->name }}</td>
            <td>{{ $income->impoExpo }}</td>
            <td>@if($income->onhold) Yes @endif</td>
            <td>{{ $income->type }}</td>
            <td>{{ $income->carrier->name }}</td>
            <td>{{ $income->reference }}</td>
            <td>{{ $income->trailer }}</td>
            <td>{{ $income->seal }}</td>
            <td>{{ $income->supplier->name }}</td>
            <td>{{ $income->invoice }}</td>
            <td>{{ $income->tracking }}</td>
            <td>{{ $income->po }}</td>
            <td>{{ $income->user }}</td>
            <td>@if($income->reviewed) Yes @endif</td>
            <td>{{ $income->reviewed_by }}</td>
            <td>@if($income->sent) Yes @endif</td>

            <td>{{ $income_row->part_number()->part_number }}</td>
            <td>{{ $income_row->units }}</td>
            <td>{{ $income_row->ump }}</td>
            <td>{{ $income_row->bundles }}</td>
            <td>{{ $income_row->umb }}</td>
            <td>{{ $income_row->net_weight }}</td>
            <td>{{ $income_row->gross_weight }}</td>
            <td>{{ $income_row->po }}</td>
            <td>{{ $income_row->desc_ing }}</td>
            <td>{{ $income_row->desc_esp }}</td>
            <td>{{ $income_row->origin_country }}</td>
            <td>{{ $income_row->fraccion }}.{{ $income_row->nico }}</td>
            <td>{{ $income_row->brand }}</td>
            <td>{{ $income_row->model }}</td>
            <td>{{ $income_row->serial }}</td>
            <td>{{ $income_row->location }}</td>
            <td>{{ $income_row->skids }}</td>
            <td>{{ $income_row->imex }}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>
</html>