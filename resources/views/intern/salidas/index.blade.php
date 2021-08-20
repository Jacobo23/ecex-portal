@extends('layouts.common')
@section('headers')
<style>
    td
    {
        text-align:center;
    }
</style>
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Salidas.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <h5 class="separtor">Filtros:</h5>

        <form action="/int/salidas" method="get">
        <div class="row">
            <div class="col-lg-2 controlDiv" >
                <label class="form-label">Cliente:</label>
                <select class="form-select" id = "txtCliente" name = "txtCliente">
                <option value=0 selected></option>
                @foreach ($clientes as $clienteOp)
                <option value="{{ $clienteOp->id }}" @if ( $cliente == $clienteOp->id) selected @endif >{{ $clienteOp->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="col-lg-2 controlDiv" >
                <label class="form-label">Rango:</label>
                <select class="form-select" id = "txtRango" name = "txtRango">
                    <option value="30" selected>30 días</option>
                    <option value="90" @if ( $rango == 90) selected @endif >90 días</option>
                    <option value="190" @if ( $rango == 190) selected @endif >6 meses</option>
                    <option value="365" @if ( $rango == 365) selected @endif >1 año</option>
                </select>
            </div>
            
            <div class="col-lg-3 controlDiv" style="">
                <label class="form-label">otros:</label>
                <input type="text" class="form-control" id="txtOtros" name="txtOtros" value="{{ $otros }}" placeholder="Factura / Pedimento / Referencia">       
            </div>

            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="submit" class="btn btn-primary">Buscar</button>     
            </div>
        </div>
            
        </form>

        <h5 class="separtor">Lista:</h5>



        <!-- como esta pantalla no contiene formularios debemos agregar uno para tener un token csrf-->
        <form method="DELETE">
        @csrf
        </form>
        

        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Salida #</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Factura</th>
                    <th scope="col">Pedimento</th>
                    <th scope="col">Referencia</th>
                    <th scope="col">Bultos</th>
                    <th scope="col">Tipo-bulto</th>
                    <th scope="col">Enviada</th>
                    <th scope="col">Folder</th>
                    @if ($can_delete) <th scope="col">Eliminar</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($outcomes as $outcome)
                <tr id="otc_row_{{ $outcome->id }}">
                    <td><a href="/int/salidas/{{ $outcome->getOutcomeNumber() }}">{{ $outcome->getOutcomeNumber() }}</a></td>
                    <td>{{ explode(" ", $outcome->cdate)[0] }}</td>
                    <td>{{ $outcome->customer->name }}</td>
                    <td>{{ $outcome->invoice }}</td>
                    <td>{{ $outcome->pediment }}</td>
                    <td>{{ $outcome->reference }}</td>
                    <td>{{ $outcome->getBultos() }}</td>
                    <td>{{ $outcome->getTipoBultos() }}</td>
                    <td>@if ($outcome->sent) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td><i class="far fa-folder-open"></i></td>
                    @if ($can_delete) <td><button onclick="eliminarSalida({{ $outcome->id }},'{{ $outcome->getOutcomeNumber() }}')"><i class="fas fa-times" style="color:red"></i></button></td> @endif
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

function eliminarSalida(id,num_salida)
{
    if(!confirm("ESTO NO ESTA PROGRAMADO ¿Desea eliminar la salida '"+num_salida+"'?"))
    {
        return;
    }
    $.ajax({url: "/int/salidas/"+id+"/delete",context: document.body}).done(function(result) 
        {
            showModal("Notificación","Salida '" + num_salida + "' eliminada");
            $("#otc_row_"+id).remove();
        });
}

</script>
@endsection