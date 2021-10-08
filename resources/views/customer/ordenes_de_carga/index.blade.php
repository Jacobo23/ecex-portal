@extends('layouts.common_customer')
@section('headers')
<style>
    td, th
    {
        text-align:center;
    }
    .oversized-col
    {
        max-width:150px;
        overflow:hidden;
    }
</style>
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ordenes de carga.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
        @if ($can_create_oc) <a href="/ext/ordenes_de_carga/create" class="btn btn-success" role="button">Crear orden <i class="fas fa-plus"></i></a> @endif
        </div>
        <br>
        <br>

        <h5 class="separtor">Lista:</h5>

        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Orden #</th>
                    <th scope="col">Regimen</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Peso Neto</th>
                    <th scope="col">Notas</th>
                    <th scope="col">Status</th>
                    <th scope="col">Descargar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($load_orders as $load_order)
                <tr id="oc_row_{{ $load_order->id }}">
                    <td>OC-{{ $load_order->id }}</td>
                    <td>{{ $load_order->regimen }}</td>
                    <td>{{ $load_order->cdate }}</td>
                    <td>{{ $load_order->get_peso_neto() }}</td>
                    <td>{{ $load_order->notes }}</td>
                    <td>{{ $load_order->status }}</td>
                    <td><a href="/ext/download_oc/{{ $load_order->id }}" class="btn" role="button"><i style="color:#34a338" class="fas fa-file-excel"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>

function descargarXLS()
{
    let path = "/ext/entradas_xls?txtRango="+$("#txtRango").val()+"&txtTracking="+$("#txtTracking").val();
    location.href = path;   
}

</script>
@endsection