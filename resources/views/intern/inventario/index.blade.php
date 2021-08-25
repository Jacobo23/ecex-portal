@extends('layouts.common')
@section('headers')
<style>
    td
    {
        text-align:center;
        font-size:0.9em;
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
            Inventario.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-full mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <h5 class="separtor">Filtros:</h5>

        <form action="/int/inventory" method="get">
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
            <!--
            <div class="col-lg-2 controlDiv" style="">
                <label class="form-label">Tracking:</label>
                <input type="text" class="form-control" id="txtOtros" name="txtOtros" value="{{ $otros }}" placeholder="Tracking">       
            </div>
            -->

            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="submit" class="btn btn-primary">Buscar</button>     
            </div>
        </div>
            
        </form>

        <h5 class="separtor"></h5>

        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Entrada #</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Materia/Equipo</th>
                    <th scope="col">Numero_de_parte</th>
                    <th scope="col">Piezas</th>
                    <th scope="col">Bultos</th>
                    <th scope="col">PesoNeto</th>
                    <th scope="col">Locación</th>
                    <th scope="col">Descripcion Ing</th>
                    <th scope="col">PO</th>
                    <th scope="col">Pais</th>
                    <th scope="col">Fraccion</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Serie</th>
                    <th scope="col">SKID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partidas as $partida)
                <tr id="inv_row_{{ $partida->id }}">
                    <td>{{ $partida->income->getIncomeNumber() }}</td>
                    <td>{{ explode(" ", $partida->income->cdate)[0] }}</td>
                    <td>{{ $partida->income->customer->name }}</td>
                    <td>{{ $partida->income->type }}</td>
                    <td>{{ $partida->part_number()->part_number }}</td>
                    <td>{{ $partida->units }}</td>
                    <td>{{ $partida->income->getBultos() }} {{ $partida->income->getTipoBultos() }}</td>
                    <td>{{ $partida->net_weight }}</td>
                    <td>{{ $partida->location }}</td>
                    <td>{{ $partida->desc_ing }}</td>
                    <td>{{ $partida->po }}</td>
                    <td>{{ $partida->origin_country }}</td>
                    <td>{{ $partida->fraccion }}.{{ $partida->nico }}</td>
                    <td>{{ $partida->pbrando }}</td>
                    <td>{{ $partida->model }}</td>
                    <td>{{ $partida->serial }}</td>
                    <td>{{ $partida->skids }}</td>
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

function editarBultos(id,control)
{return;
    if(!confirm("¿Desea eliminar la entrada '"+num_entrada+"'?"))
    {
        return;
    }
    $.ajax({url: "/int/entradas/"+id+"/delete",context: document.body}).done(function(result) 
        {
            if(result != "")
            {
                showModal("Notificación",result);
            }
            else
            {
                showModal("Notificación","Entrada '" + num_entrada + "' eliminada");
                $("#inc_row_"+id).remove();
            }
            
        });
}

</script>
@endsection