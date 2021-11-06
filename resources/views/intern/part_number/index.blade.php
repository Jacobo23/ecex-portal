@extends('layouts.common')
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
            Números de parte.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-full mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <form id = "frmPartNumber" action="/part_number" method="get">
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

            <div class="col-lg-2 controlDiv" style="">
                <label class="form-label">#NP / Descripción:</label>
                <input type="text" class="form-control" id="txtDesc" name="txtDesc" value="{{ $desc ?? '' }}" placeholder="#NP / Desc. Inglés / Español">       
            </div>

            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="button" class="btn btn-primary" onclick="buscar()">Buscar</button>     
            </div>
            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <a href="/part_number/create" class="btn btn-success" role="button" aria-pressed="true"><i class="fas fa-plus"></i></a>
            </div>
            
        </div>
        <br><br>

            <div class="row" >
                <input type="hidden" id="txtTab" name="txtTab" value="{{ $tab ?? 1}}">

                <div class="col-lg-10 controlDiv" style="overflow: auto; text-align:center; left:100px; position:relative;">
                    <div class="btn-group me-2" id="div_btns_partidas" role="group">
                    <button type='button' class='btn btn-outline-primary btnIncomeRow' onclick='goPrev()' id='btnTab_prev'><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></button>
                        @php
                        if(isset($tab_count))
                        {
                            for ($i = 1; $i <= $tab_count+1; $i++)
                            {
                                $active_tab = "";
                                if($tab == $i)
                                {
                                    $active_tab = "active";
                                }
                                echo "<button type='button' class='btn btn-outline-secondary btnIncomeRow ".$active_tab."' onclick='goTab(".$i.")' id='btnTab_".$i."' >".$i."</button>";
                            }
                        }
                        @endphp
                        <button type='button' class='btn btn-outline-primary btnIncomeRow' onclick='goNext()' id='btnTab_prev'><span aria-hidden="true">&raquo;</span><span class="sr-only">Previous</span></button>
                    </div>
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
                    <th scope="col">Número_de_Parte</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">um</th>
                    <th scope="col">Peso u.</th>
                    <th scope="col">Desc_Ing</th>
                    <th scope="col">Desc_Esp</th>
                    <th scope="col">Pais</th>
                    <th scope="col">Fraccion.nico</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Serie</th>
                    <th scope="col">IMMEX</th>
                    <th scope="col">Regimen</th>
                    <th scope="col">observaciones</th>
                    @if ($can_edit) <th scope="col">Editar</th> @endif
                    @if ($can_delete) <th scope="col">Eliminar</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($part_numbers as $part_number)
                <tr id="pn_row_{{ $part_number->id }}">
                    <td style="font-size:0.9em">{{ $part_number->part_number }}</td>
                    <td>{{ $part_number->customer->name }}</td>
                    <td>{{ $part_number->um }}</td>
                    <td>{{ $part_number->unit_weight }}</td>
                    <td style="font-size:0.8em; min-width:110px;">{{ $part_number->desc_ing }}</td>
                    <td style="font-size:0.8em; min-width:110px;">{{ $part_number->desc_esp }}</td>
                    <td>{{ $part_number->origin_country }}</td>
                    <td>{{ $part_number->fraccion }}.{{ $part_number->nico }}</td>
                    <td>{{ $part_number->brand }}</td>
                    <td>{{ $part_number->model }}</td>
                    <td>{{ $part_number->serial }}</td>
                    <td>{{ $part_number->imex }}</td>
                    <td>{{ $part_number->regime }}</td>
                    <td style="font-size:0.7em; min-width:110px;">{{ $part_number->fraccion_especial }}</td>
                    @if ($can_edit) <td><a href="/part_number/{{ $part_number->id }}/edit_existing" class="btn btn-light" role="button" aria-pressed="true"><i class="fas fa-edit"></i></a></td> @endif
                    @if ($can_delete) <td><button class="btn btn-light" type='button' onclick="eliminar({{ $part_number->id }})"><i class="fas fa-times" style="color:red"></i></button></td> @endif
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


function eliminar(id)
{
    if(!confirm("¿Desea eliminar el número de parte?"))
    {
        return;
    }
    let token = $("[name='_token']").val();
    $.ajax(
        {
            url: "/part_number/"+id,
            type: 'DELETE',
            data: {
                "_token": token,
            },
            success: function (response){
                showModal("Notificación",response);
                if(response == "Eliminado!")
                {
                    $("#pn_row_"+id).remove();
                }
            }
        });
}

function buscar()
{
    $("#txtTab").val("1");
    $("#frmPartNumber").submit();
}
function goTab(tab)
{
    $("#txtTab").val(tab);
    $("#frmPartNumber").submit();
}
function goPrev()
{
    var current_tab = Number($("#txtTab").val());    
    $("#txtTab").val(current_tab-1);
    $("#frmPartNumber").submit();
}
function goNext()
{
    var current_tab = Number($("#txtTab").val());
    $("#txtTab").val(current_tab+1);
    $("#frmPartNumber").submit();
}
function editar(id)
{
    location.href = "/part_number/"+id+"/edit";
}


</script>
@endsection