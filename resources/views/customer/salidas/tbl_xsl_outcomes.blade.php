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
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Salida</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Fecha</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Cliente</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:20px">Transportista</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:8px">Caja</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:8px">Placas</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Sello</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Factura</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Pedimento</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Referencia</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:25px">Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($outcomes as $outcome)
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
            <td>{{ $outcome->observations }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</html>