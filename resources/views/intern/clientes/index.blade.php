@extends('layouts.common')
@section('headers')
<style>
    td
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
            Clientes.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <div class="row">
            <div class="col-lg-2 controlDiv">
                <a href="/int/catalog/customers/create" class="btn btn-success" role="button" aria-pressed="true">Crear <i class="fas fa-plus"></i></a>
            </div>
        </div>
            
        <h5 class="separtor">Lista:</h5>

        <!-- como esta pantalla no contiene formularios debemos agregar uno para tener un token csrf-->
        <form method="DELETE">
        @csrf
        </form>
        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Cliente</th>
                    <th scope="col">Correos 1</th>
                    <th scope="col">Correos 2</th>
                    @if ($can_edit) <th scope="col">Editar</th> @endif
                    @if ($can_delete) <th scope="col">Eliminar</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr id="ctm_row_{{ $customer->id }}">
                    <td>{{ $customer->name }}</td>
                    <td><input type="text" class="form-control" style="width:500px" value="{{ $customer->emails }}"/></td>
                    <td><input type="text" class="form-control" style="width:500px" value="{{ $customer->emails_add }}"/></td>
                    @if ($can_edit) <td><a href="/int/catalog/customers/{{ $customer->id }}/edit" class="btn btn-light" role="button" aria-pressed="true"><i class="fas fa-edit"></i></a></td> @endif
                    @if ($can_delete) <td><button class="btn btn-light" type='button' onclick="eliminar({{ $customer->id }})"><i class="fas fa-times" style="color:red"></i></button></td> @endif
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