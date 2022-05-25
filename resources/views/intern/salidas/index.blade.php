@extends('layouts.common')
@section('headers')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
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
            <div class="col-lg-3 controlDiv" >
                <label class="form-label">Rango:</label>
                <input class="form-select" type="text" name="txtRango" id="txtRango" value="{{ $rango }}"/>
            </div>
            
            <div class="col-lg-4 controlDiv" style="">
                <label class="form-label">otros:</label>
                <input type="text" class="form-control" id="txtOtros" name="txtOtros" value="{{ $otros }}" placeholder="Entrada/# de parte/Factura/Pedimento/Referencia">       
            </div>

            <div class="col-lg-1 controlDiv" style="position:relative;top:30px;">
                <button type="submit" class="btn btn-primary">Buscar</button>     
            </div>
            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="button" class="btn btn-success" onclick="descargarXLS()">Descargar <i class="far fa-file-excel"></i></button>     
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
                    <th scope="col">Folder</th>
                    <th scope="col">Ocultar</th>
                    @if ($can_delete) <th scope="col">Eliminar</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($outcomes as $outcome)
                <tr id="otc_row_{{ $outcome->id }}">
                    <td><a href="/int/salidas/{{ $outcome->getOutcomeNumber(false) }}">{{ $outcome->getOutcomeNumber(true) }}</a></td>
                    <td>{{ date_format(date_create(explode(" ", $outcome->cdate)[0]),"m/d/y") }}</td>
                    <td>{{ $outcome->customer->name }}</td>
                    <td>{{ $outcome->invoice }}</td>
                    <td>{{ $outcome->pediment }}</td>
                    <td>{{ $outcome->reference }}</td>
                    <td>{{ $outcome->getBultos() }}</td>
                    <td>{{ $outcome->getTipoBultos() }}</td>
                    <td id="adjuntos_btn_{{ $outcome->id }}" ><button type="button" class="btn btn-light" onclick="showAdjuntos('adjuntos_outcome_{{ $outcome->id }}')"><i class="far fa-folder-open"></i></button></td>
                    <td id="adjuntos_outcome_{{ $outcome->id }}" class="td_adjuntos" style="display:none">
                        @php
                        $packinglist_path='/public/salidas/'.$outcome->getOutcomeNumber(false).'/packing_list/';
                        if (Storage::exists($packinglist_path)) 
                        {
                            echo "<br>";
                            $packinglists = Storage::files($packinglist_path);
                            foreach ($packinglists as $packinglist) 
                            {
                                $pck_file_name_array=explode('/',$packinglist);
                                $pck_file_name=$pck_file_name_array[count($pck_file_name_array)-1];

                                echo "<div class='img_card col-lg-12' style='padding:10px'>";
                                echo "    <div class='img_card_top'>";
                                echo "        <h6><b>".$pck_file_name."</b></h6>"; 
                                echo "    </div>";
                                echo "    <p><a href='/download_pakinglist_outcome/".$outcome->getOutcomeNumber(false)."/".$pck_file_name."'><i class='fas fa-arrow-circle-down'></i></a><strong>Tamaño: </strong> ". round(Storage::size($packinglist)/1000000,2,PHP_ROUND_HALF_UP ) ." Mb</p>";
                                echo "</div>";
                            }
                        }
                        $outcome_imgs_paths='public/salidas/'.$outcome->getOutcomeNumber(false).'/images/';
                        $outcome_imgs = Storage::files($outcome_imgs_paths);
                        echo "<br>";

                        foreach ($outcome_imgs as $outcome_img) 
                        {
                            $img_file_name_array=explode('/',$outcome_img);

                            $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                            $img_file_url='storage/salidas/'.$outcome->getOutcomeNumber(false).'/images/'.$img_file_name;

                            echo "<div class='img_card col-lg-5' >";
                            echo "    <div class='img_card_top'>";
                            echo "        <h6><b>".$img_file_name."</b></h6>"; 
                            echo "    </div>";
                            echo "    <img src_aux='".asset($img_file_url)."'>";
                            echo "</div>";
                        }
                        @endphp
                    </td>
                    @if ($outcome->hidden) 
                        @if($can_hide) 
                            <td><button onclick="revelarSalida({{ $outcome->id }},'{{ $outcome->getOutcomeNumber(false) }}')">Oculta, Mostrar <i class="far fa-eye"></i></button></td> 
                        @else
                            <td>Oculta <i class="fas fa-eye-slash"></i></td> 
                        @endif
                    @else
                        @if($can_hide) 
                            <td><button onclick="ocultarSalida({{ $outcome->id }},'{{ $outcome->getOutcomeNumber(false) }}')">Visible, Ocultar <i class="fas fa-eye-slash"></i></button></td>
                        @else
                            <td>visible <i class="far fa-eye"></i></td> 
                        @endif
                    @endif
                    @if ($can_delete) <td><button onclick="eliminarSalida({{ $outcome->id }},'{{ $outcome->getOutcomeNumber(false) }}')"><i class="fas fa-times" style="color:red"></i></button></td> @endif
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
    if(!confirm("¿Desea eliminar la salida '"+num_salida+"'?"))
    {
        return;
    }
    $.ajax({url: "/int/salidas/"+id+"/delete",context: document.body}).done(function(result) 
        {
            showModal("Notificación","Salida '" + num_salida + "' eliminada");
            $("#otc_row_"+id).remove();
        });
}

function descargarXLS()
{
    let path = "/int/salidas_xls?txtCliente="+$("#txtCliente").val()+"&txtRango="+$("#txtRango").val()+"&txtOtros="+$("#txtOtros").val();
    location.href = path;   
}

function showAdjuntos(content_row)
{
    var html = $("#"+content_row).html();   
    showModal("Adjuntos",html.replace(/src_aux/g, "src"));
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

function ocultarSalida(id,num_salida)
{
    if(!confirm("¿Desea ocultar la salida '"+num_salida+"' para el cliente?"))
    {
        return;
    }
    $.ajax({url: "/int/salidas/"+id+"/hide",context: document.body}).done(function(result) 
        {
            if(result != "")
            {
                showModal("Notificación",result);
            }
            else
            {
                showModal("Notificación","Salida '" + num_salida + "' oculta para el cliente");
                location.href = "/int/salidas/";
            }
        });
}
function revelarSalida(id,num_salida)
{
    if(!confirm("¿Desea revelar la salida '"+num_salida+"' para el cliente?"))
    {
        return;
    }
    $.ajax({url: "/int/salidas/"+id+"/unhide",context: document.body}).done(function(result) 
        {
            if(result != "")
            {
                showModal("Notificación",result);
            }
            else
            {
                showModal("Notificación","Salida '" + num_salida + "' visible para el cliente");
                location.href = "/int/salidas/";
            }
        });
}

$(document).ready(function(){
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