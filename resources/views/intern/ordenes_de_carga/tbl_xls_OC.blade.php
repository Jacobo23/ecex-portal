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
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Entrada</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Proveedor</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Numero de parte</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Cantidad</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Um. piezas</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">Conversion</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center;">U.M</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Bultos*</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Tipo bulto</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Peso neto</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Descripcion Ing</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Descripcion Esp</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">PO</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Pais</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Fraccion-nico</th>
            <th style="background-color: #ba1600; color:#ffffff; font-weight:bold; text-align:center; ">Lote</th>
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

            <td>{{ $oc_row->convert_unit() }}</td>
            <td>{{ $oc_row->converting_unit() }}</td>

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







