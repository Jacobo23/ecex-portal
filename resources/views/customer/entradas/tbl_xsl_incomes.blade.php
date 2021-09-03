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
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Entrada</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Fecha</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Cliente</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Impo/Expo</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Materia/Equipo</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:20px">Transportista</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Referencia</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:8px">Caja</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Sello</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Proveedor</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Factura</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Tracking</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">PO</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Urgente</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">On hold</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($incomes as $income)
        <tr>
            <td>{{ $income->getIncomeNumber() }}</td>
            <td>{{ explode(" ", $income->cdate)[0] }}</td>
            <td>{{ $income->customer->name }}</td>
            <td>{{ $income->impoExpo }}</td>
            <td>{{ $income->type }}</td>
            <td>{{ $income->carrier->name }}</td>
            <td>{{ $income->reference }}</td>
            <td>{{ $income->trailer }}</td>
            <td>{{ $income->seal }}</td>
            <td>{{ $income->supplier->name }}</td>
            <td>{{ $income->invoice }}</td>
            <td>{{ $income->tracking }}</td>
            <td>{{ $income->po }}</td>
            <td>@if($income->urgent) Yes @endif</td>
            <td>@if($income->onhold) Yes @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>
</html>