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
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Proveedor</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Numero de parte</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Cantidad</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:20px">Um. piezas</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Bultos*</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Tipo bulto</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:10px">Peso neto</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:25px">Descripcion Ing</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:25px">Descripcion Esp</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">PO</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:8px">Pais</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Fraccion-nico</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; width:15px">Lote</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Marca</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Modelo</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Serie</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($oc_rows as $oc_row)
        <tr>
            <td>{{ $oc_row->income_row->income->getIncomeNumber() }}</td>
            <td>{{ $oc_row->income_row->income->supplier->name }}</td>
            <td>{{ $oc_row->income_row->part_number()->part_number }}</td>
            <td>{{ $oc_row->units }}</td>
            <td>{{ $oc_row->income_row->ump }}</td>
            <td>{{ $oc_row->income_row->bundles }}</td>
            <td>{{ $oc_row->income_row->umb }}</td>
            <td>{{ $oc_row->get_peso_neto() }}</td>
            <td>{{ $oc_row->income_row->desc_ing }}</td>
            <td>{{ $oc_row->income_row->desc_esp }}</td>
            <td>{{ $oc_row->income_row->po }}</td>
            <td>{{ $oc_row->income_row->origin_country }}</td>
            <td>{{ $oc_row->income_row->fraccion }}-{{ $oc_row->income_row->nico }}</td>
            <td>{{ $oc_row->income_row->lot }}</td>
            <td>{{ $oc_row->income_row->brand }}</td>
            <td>{{ $oc_row->income_row->model }}</td>
            <td>{{ $oc_row->income_row->serial }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</html>







