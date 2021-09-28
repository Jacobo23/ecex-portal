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
                    <option value="15" selected>15 días</option>
                    <option value="30" @if ( $rango == 30) selected @endif >30 días</option>
                    <option value="90" @if ( $rango == 90) selected @endif >90 días</option>
                    <option value="190" @if ( $rango == 190) selected @endif >6 meses</option>
                    <option value="365" @if ( $rango == 365) selected @endif >1 año</option>
                    <option value="1095" @if ( $rango == 1095) selected @endif >3 años</option>
                </select>
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
                    @if ($can_delete) <th scope="col">Eliminar</th> @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($outcomes as $outcome)
                <tr id="otc_row_{{ $outcome->id }}">
                    <td><a href="/int/salidas/{{ $outcome->getOutcomeNumber(false) }}">{{ $outcome->getOutcomeNumber(true) }}</a></td>
                    <td>{{ explode(" ", $outcome->cdate)[0] }}</td>
                    <td>{{ $outcome->customer->name }}</td>
                    <td>{{ $outcome->invoice }}</td>
                    <td>{{ $outcome->pediment }}</td>
                    <td>{{ $outcome->reference }}</td>
                    <td>{{ $outcome->getBultos() }}</td>
                    <td>{{ $outcome->getTipoBultos() }}</td>
                    <td><button type="button" class="btn btn-light" onclick="showAdjuntos('adjuntos_outcome_{{ $outcome->id }}')"><i class="far fa-folder-open"></i></button></td>
                    <td id="adjuntos_outcome_{{ $outcome->id }}" style="display:none">
                        @php
                        $packinglist_path='/public/salidas/'.$outcome->getOutcomeNumber(false).'/packing_list/packing-list.pdf';
                        if (Storage::exists($packinglist_path)) 
                        {
                            echo "<br>";
                            echo "<div class='img_card col-lg-12' style='padding:10px'>";
                            echo "    <div class='img_card_top'>";
                            echo "        <h6><b>Packing list</b></h6>"; 
                            echo "    </div>";
                            echo "    <p><a href='/download_pakinglist_outcome/".$outcome->getOutcomeNumber(false)."'><i class='fas fa-arrow-circle-down'></i></a><strong>Tamaño: </strong> ". round(Storage::size($packinglist_path)/1000000,2,PHP_ROUND_HALF_UP ) ." Mb</p>";
                            echo "</div>";
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
                            echo "    <img src='".asset($img_file_url)."'>";
                            echo "</div>";
                        }
                        @endphp
                    </td>
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
    showModal("Adjuntos",html);
}

</script>
@endsection