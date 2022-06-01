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
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Cliente</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Materia/Equipo</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">On hold</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Proveedor</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Numero_de_parte</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Piezas</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">U.M</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Conversion</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">U.M</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Bultos</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Tipo bulto</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">PesoNeto</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Locación</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Descripcion Ing</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Descripcion Esp</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">PO</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Pais</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Fraccion</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Marca</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Modelo</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Serie</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">SKID</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Observaciones Entrada</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Observaciones Partida</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($partidas as $partida)
        <tr id="inv_row_{{ $partida->id }}">
            <td>{{ $partida->income->getIncomeNumber() }}</td>
            <td>{{ explode(" ", $partida->income->cdate)[0] }}</td>
            <td>{{ $partida->income->customer->name }}</td>
            <td>{{ $partida->income->type }}</td>
            <td>{{ $partida->income->onhold }}</td>
            <td>{{ $partida->income->supplier->name }}</td>
            <td>{{ $partida->part_number()->part_number }}</td>
            <td>{{ $partida->units }}</td>
            <td>{{ $partida->ump }}</td>
            
            <td>{{ $partida->convert_unit() }}</td>
            <td>{{ $partida->converting_unit() }}</td>

            <td>{{ $partida->getBultos() }}</td>
            <td>{{ $partida->umb }}</td>
            <td>{{ $partida->net_weight }}</td>
            <td>{{ $partida->location }}</td>
            <td>{{ $partida->desc_ing }}</td>
            <td>{{ $partida->desc_esp }}</td>
            <td>{{ $partida->po }}</td>
            <td>{{ $partida->origin_country }}</td>
            <td>{{ $partida->fraccion }}.{{ $partida->nico }}</td>
            <td>{{ $partida->brand }}</td>
            <td>{{ $partida->model }}</td>
            <td>{{ $partida->serial }}</td>
            <td>{{ $partida->skids }}</td>
            <td>{{ $partida->observations }}</td>
            <td>{{ $partida->income->observations }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</html>