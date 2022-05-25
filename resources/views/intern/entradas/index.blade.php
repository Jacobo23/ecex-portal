@extends('layouts.common')
@section('headers')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<style>
    td
    {
        text-align:center;
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
            Entradas.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
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
            <div class="col-lg-3 controlDiv" >
                <label class="form-label">Rango:</label>
                <input class="form-select" type="text" name="txtRango" id="txtRango" value="{{ $rango }}"/>
            </div>

            
            <div class="col-lg-2 controlDiv" style="">
                <label class="form-label">Tracking:</label>
                <input type="text" class="form-control" id="txtTracking" name="txtTracking" value="{{ $tracking }}" placeholder="Tracking">       
            </div>

            <div class="col-lg-2 controlDiv" >
                <label class="form-label">Status</label>
                <select class="form-select" id = "txtStatus" name = "txtStatus">
                    <option value="todo" @if ( $en_inventario == 'todo') selected @endif>Todo</option>
                    <option value="Revision Pendiente" @if ( $en_inventario == 'Revision Pendiente') selected @endif>Revision Pendiente</option>
                    <option value="en inventario" @if ( $en_inventario == 'en inventario') selected @endif>En inventario</option>
                    <option value="cerrada" @if ( $en_inventario == 'cerrada') selected @endif>Cerradas</option>
                </select>
            </div>


            <div class="col-lg-1 controlDiv" style="position:relative;top:30px;">
                <button type="submit" class="btn btn-primary">Buscar</button>     
            </div>
            <div class="col-lg-1 controlDiv" style="position:relative;top:30px;">
                <button type="button" class="btn btn-success" onclick="descargarXLS()">Descargar <i class="far fa-file-excel"></i></button>     
            </div>
        </div>
            
        </form>

        <h5 class="separtor">Lista:</h5>

        
        <div class="row">
            <div class="col-lg-10">
            </div>
            <div class="col-lg-2">
                <input type="text" class="form-control" id="txtQuickSearch" placeholder="Busca rapida">       
            </div>
        </div>
        <br>

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
                    <th scope="col">Proveedor</th>
                    <th scope="col">PO</th>
                    <th scope="col">Tracking</th>
                    <th scope="col">Bultos</th>
                    <th scope="col">Materia/Equipo</th>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="chkEnviada" onclick="filtrarEnviadas()"> Enviada</th>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="chkRevisada" onclick="filtrarRevisadas()"> Revisada</th>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="chkUrgente" onclick="filtrarUrgentes()"> Urgente</th>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="chkOnhold" onclick="filtrarOnhold()"> On-hold</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Folder</th>
                    <th scope="col" style="display:none">adjuntos</th>
                    <th scope="col">Ocultar</th>
                    @if ($can_delete) <th scope="col">Eliminar</th> @endif
                </tr>
            </thead>
            <tbody id="tbl_Incomes">
                @foreach ($incomes as $income)
                <tr id="inc_row_{{ $income->id }}" class="tr_tbl @if ( $income->get_color_fila_estado() != '') table-{{ $income->get_color_fila_estado() }} @endif ">
                    <td><a href="/int/entradas/{{ $income->getIncomeNumber() }}">{{ $income->getIncomeNumber() }}</a></td>
                    <td>{{ date_format(date_create(explode(" ", $income->cdate)[0]),"m/d/y") }}</td>
                    <td>{{ $income->getDiasTrascurridos() }}</td>
                    <td>{{ $income->customer->name }}</td>
                    <td>{{ $income->supplier->name }}</td>
                    <td>{{ $income->po }}</td>
                    <td>{{ $income->tracking }}</td>
                    <td>{{ $income->getBultosOriginales() }} {{ $income->getTipoBultos() }}</td>
                    <td>{{ $income->type }}</td>
                    <td id="tdEnviada_{{ $income->id }}">@if ($income->sent) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td id="tdRevisada_{{ $income->id }}">@if ($income->reviewed) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td id="tdUrgente_{{ $income->id }}">@if ($income->urgent) <i class="fas fa-check-square" style="color:red"></i> @endif</td>
                    <td id="tdOnhold_{{ $income->id }}">@if ($income->onhold) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td><a class="btn " href="/int/balance?entrada={{ $income->getIncomeNumber() }}"><i class="fas fa-balance-scale"></i></a></td>
                    <td id="adjuntos_btn_{{ $income->id }}"><button type="button" class="btn btn-light" onclick="showAdjuntos('adjuntos_income_{{ $income->id }}')"><i class="far fa-folder-open"></i></button></td>
                    <td id="adjuntos_income_{{ $income->id }}" class="td_adjuntos" style="display:none">
                        @php
                        $packinglist_path='/public/entradas/'.$income->getIncomeNumber().'/packing_list/packing-list.pdf';
                        if (Storage::exists($packinglist_path)) 
                        {
                            echo "<br>";
                            echo "<div class='img_card col-lg-12' style='padding:10px'>";
                            echo "    <div class='img_card_top'>";
                            echo "        <h6><b>Packing list</b></h6>"; 
                            echo "    </div>";
                            echo "    <p><a href='/download_pakinglist/".$income->getIncomeNumber()."'><i class='fas fa-arrow-circle-down'></i></a><strong>Tamaño: </strong> ". round(Storage::size($packinglist_path)/1000000,2,PHP_ROUND_HALF_UP ) ." Mb</p>";
                            echo "</div>";
                        }
                        $income_imgs_paths='public/entradas/'.$income->getIncomeNumber().'/images/';
                        $income_imgs = Storage::files($income_imgs_paths);
                        echo "<br>";

                        foreach ($income_imgs as $income_img) 
                        {
                            $img_file_name_array=explode('/',$income_img);

                            $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                            $img_file_url='storage/entradas/'.$income->getIncomeNumber().'/images/'.$img_file_name;

                            echo "<div class='img_card col-lg-5' >";
                            echo "    <div class='img_card_top'>";
                            echo "        <h6><b>".$img_file_name."</b></h6>"; 
                            echo "    </div>";
                            echo "    <img src_aux='".asset($img_file_url)."'>";
                            echo "</div>";
                        }
                        @endphp
                    </td>

                    @if ($income->hidden) 
                        @if($can_hide) 
                            <td><button onclick="revelarEntrada({{ $income->id }},'{{ $income->getIncomeNumber() }}')">Oculta, Mostrar <i class="far fa-eye"></i></button></td> 
                        @else
                            <td>Oculta <i class="fas fa-eye-slash"></i></td> 
                        @endif
                    @else
                        @if($can_hide) 
                            <td><button onclick="ocultarEntrada({{ $income->id }},'{{ $income->getIncomeNumber() }}')">Visible, Ocultar <i class="fas fa-eye-slash"></i></button></td>
                        @else
                            <td>visible <i class="far fa-eye"></i></td> 
                        @endif
                    @endif

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
function ocultarEntrada(id,num_entrada)
{
    if(!confirm("¿Desea ocultar la entrada '"+num_entrada+"' para el cliente?"))
    {
        return;
    }
    $.ajax({url: "/int/entradas/"+id+"/hide",context: document.body}).done(function(result) 
        {
            if(result != "")
            {
                showModal("Notificación",result);
            }
            else
            {
                showModal("Notificación","Entrada '" + num_entrada + "' oculta para el cliente");
                location.href = "/int/entradas/";
            }
            
        });
}
function revelarEntrada(id,num_entrada)
{
    if(!confirm("¿Desea revelar la entrada '"+num_entrada+"' para el cliente?"))
    {
        return;
    }
    $.ajax({url: "/int/entradas/"+id+"/unhide",context: document.body}).done(function(result) 
        {
            if(result != "")
            {
                showModal("Notificación",result);
            }
            else
            {
                showModal("Notificación","Entrada '" + num_entrada + "' visible para el cliente");
                location.href = "/int/entradas/";
            }
            
        });
}



function descargarXLS()
{
    let path = "/int/entradas_xls?txtCliente="+$("#txtCliente").val()+"&txtRango="+$("#txtRango").val()+"&txtTracking="+$("#txtTracking").val()+"&txtStatus="+$("#txtStatus").val();
    // if($('#chkInventario').prop('checked'))
    // {
    //     path += "&chkInventario=true";
    // }
    location.href = path;   
}

function showAdjuntos(content_row)
{
    var html = $("#"+content_row).html();   
    showModal("Adjuntos",html.replace(/src_aux/g, "src"));
}

function filtrarEnviadas()
{
    $(".tr_tbl").each(function()
        {
            $(this).show();
        });

    $('#chkRevisada').prop('checked', false);
    $('#chkUrgente').prop('checked', false);
    $('#chkOnhold').prop('checked', false);

    if($('#chkEnviada').prop('checked'))
    {        

        $(".tr_tbl").each(function()
        {
            var index = $(this).attr('id').split("_")[2];
            if( $("#tdEnviada_"+index).html() == "")
            {
                $("#inc_row_"+index).hide();
            }
        });
    }
}



function showFolderIcon()
{
    $(".td_adjuntos").each(function()
        {
            if($(this).html().trim() == "<br>")
            {
                var id = $(this).attr('id').split("_")[2];
                $("#adjuntos_btn_"+id).html("");
            }
        });
}

function filtrarRevisadas()
{
    $(".tr_tbl").each(function()
        {
            $(this).show();
        });

    $('#chkEnviada').prop('checked', false);
    $('#chkUrgente').prop('checked', false);
    $('#chkOnhold').prop('checked', false);

    if($('#chkRevisada').prop('checked'))
    {        
        $(".tr_tbl").each(function()
        {
            var index = $(this).attr('id').split("_")[2];
            if( $("#tdRevisada_"+index).html() == "")
            {
                $("#inc_row_"+index).hide();
            }
        });
    }
}

function filtrarUrgentes()
{
    $(".tr_tbl").each(function()
        {
            $(this).show();
        });

    $('#chkEnviada').prop('checked', false);
    $('#chkRevisada').prop('checked', false);
    $('#chkOnhold').prop('checked', false);

    if($('#chkUrgente').prop('checked'))
    {        
        $(".tr_tbl").each(function()
        {
            var index = $(this).attr('id').split("_")[2];
            if( $("#tdUrgente_"+index).html() == "")
            {
                $("#inc_row_"+index).hide();
            }
        });
    }
}

function filtrarOnhold()
{
    $(".tr_tbl").each(function()
        {
            $(this).show();
        });

    $('#chkEnviada').prop('checked', false);
    $('#chkRevisada').prop('checked', false);
    $('#chkUrgente').prop('checked', false);

    if($('#chkOnhold').prop('checked'))
    {        
        $(".tr_tbl").each(function()
        {
            var index = $(this).attr('id').split("_")[2];
            if( $("#tdOnhold_"+index).html() == "")
            {
                $("#inc_row_"+index).hide();
            }
        });
    }
}

$(document).ready(function(){
  $("#txtQuickSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tbl_Incomes tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  showFolderIcon();

  const picker = new Litepicker({ 
    element: document.getElementById('txtRango'),
    singleMode: false,
    format: 'MM/DD/YYYY',
    numberOfMonths: 2,
    numberOfColumns: 2,
    scrollToDate: false,
    
  });

  

});



</script>


@endsection