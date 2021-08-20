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
            Entradas.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <h5 class="separtor">Filtros:</h5>

        <form action="/int/entradas" method="get">
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
            
            <div class="col-lg-2 controlDiv" style="">
                <label class="form-label">Tracking:</label>
                <input type="text" class="form-control" id="txtTracking" name="txtTracking" value="{{ $tracking }}" placeholder="Tracking">       
            </div>
            <div class="col-lg-4 controlDiv form-check form-switch" style="position:relative;top:40px;">
                <input class="form-check-input" type="checkbox" id="chkInventario" name="chkInventario" {{ ($en_inventario) ? "checked" : "" }}>
                <label class="form-check-label" for="chkInventario">Inventario <small>*esto puede demorar la busqueda.</small></label>
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
                    <th scope="col">Entrada #</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Dias</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Tracking</th>
                    <th scope="col">Bultos</th>
                    <th scope="col">Tipo-bulto</th>
                    <th scope="col">Materia/Equipo</th>
                    <th scope="col">Enviada</th>
                    <th scope="col">Revisada</th>
                    <th scope="col">Urgente</th>
                    <th scope="col">On-hold</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Folder</th>
                    @if ($can_delete) <th scope="col">Eliminar</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($incomes as $income)
                <tr id="inc_row_{{ $income->id }}">
                    <td><a href="/int/entradas/{{ $income->getIncomeNumber() }}">{{ $income->getIncomeNumber() }}</a></td>
                    <td>{{ explode(" ", $income->cdate)[0] }}</td>
                    <td>{{ $income->getDiasTrascurridos() }}</td>
                    <td>{{ $income->customer->name }}</td>
                    <td>{{ $income->tracking }}</td>
                    <td>{{ $income->getBultos() }}</td>
                    <td>{{ $income->getTipoBultos() }}</td>
                    <td>{{ $income->type }}</td>
                    <td>@if ($income->sent) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td>@if ($income->reviewed_by) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td>@if ($income->urgent) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td>@if ($income->onhold) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td><i class="fas fa-balance-scale"></i></td>
                    <td><i class="far fa-folder-open"></i></td>
                    @if ($can_delete) <td><button onclick="eliminarEntrada({{ $income->id }},'{{ $income->getIncomeNumber() }}')"><i class="fas fa-times" style="color:red"></i></button></td> @endif
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

function eliminarEntrada(id,num_entrada)
{
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