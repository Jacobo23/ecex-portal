@extends('layouts.common')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear salida.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

    <h5 class="separtor">Encabezado</h5>

    <form id="encabezadoForm" action="/int/salidas" method="post">
    @csrf

    <div class="row">
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Numero de Salida:</label>
            <input type="text" class="form-control" id="txtNumSalida" name="txtNumSalida" value="{{ $numero_de_salida ?? '' }}" readonly style="text-align:center;">       
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Regimen:</label>
            <select class="form-select" id = "txtRegimen" name = "txtRegimen">
            <option value=0 selected></option>
            @foreach ($regimes as $regimeOp)
            <option value="{{ $regimeOp->name }}" @php if(isset($outcome)){if($outcome->regime === $regimeOp->name){echo "selected";}}@endphp >{{ $regimeOp->name }}</option>
            @endforeach
            </select>
        </div>
        

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Fecha:</label>
            <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="@if (isset($outcome)){{ explode(' ',$outcome->cdate)[0] }}@endif">
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Cliente:</label>
            <select class="form-select" id = "txtCliente" name = "txtCliente">
            <option value=0 selected></option>
            @foreach ($clientes as $clienteOp)
            <option value="{{ $clienteOp->id }}" @php if(isset($outcome)){if($outcome->customer_id === $clienteOp->id){echo "selected";}}@endphp >{{ $clienteOp->name }}</option>
            @endforeach
            </select>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Transportista:</label>
            <select class="form-select" id = "txtTransportista" name = "txtTransportista">
            <option value=0 selected></option>
            @foreach ($transportistas as $transportistaOp)
            <option value="{{ $transportistaOp->id }}" @php if(isset($outcome)){if($outcome->carrier_id === $transportistaOp->id){echo "selected";}}@endphp >{{ $transportistaOp->name }}</option>
            @endforeach
            </select>
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Caja:</label>
            <input type="text" class="form-control" id="txtCaja" name="txtCaja" value="{{ $outcome->trailer ?? '' }}">       
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Sello:</label>
            <input type="text" class="form-control" id="txtSello" name="txtSello" value="{{ $outcome->seal ?? '' }}">       
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Factura:</label>
            <input type="text" class="form-control" id="txtFactura" name="txtFactura" value="{{ $outcome->invoice ?? '' }}">       
        </div>
        
    </div>

    <div class="row">

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Pedimento:</label>
            <input type="text" class="form-control" id="txtPedimento" name="txtPedimento" value="{{ $outcome->pediment ?? '' }}">       
        </div>
        
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Referencia:</label>
            <input type="text" class="form-control" id="txtReferencia" name="txtReferencia" value="{{ $outcome->reference ?? '' }}">       
        </div>
        
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Placas:</label>
            <input type="text" class="form-control" id="txtPlacas" name="txtPlacas" value="{{ $outcome->plate ?? '' }}">       
        </div>
        <div class="col-lg-2 controlDiv" style="">
            <label class="form-label">Recibido por:</label>
            <input type="text" class="form-control" id="txtRecibidoPor" name="txtRecibidoPor" value="{{ $outcome->received_by ?? '' }}">       
        </div>

        <div class="col-lg-2 controlDiv" >
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="chkDescontar" id="chkDescontar" name="chkDescontar" @isset($outcome->onhold){{ ($outcome->discount)?'checked':'' }}@endisset>
                <label class="form-check-label">Descontar</label>
            </div>      
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="2">{{ $outcome->observations ?? '' }}</textarea>
    </div>

    </form>

    <div class="row">

        <div class="col-lg-2 controlDiv" >

            <button type="button" class="btn btn-secondary" onclick="packingBtnClick()">Packing list <i class="far fa-file-alt"></i></button>
            <br>
            <div style="display:none">
                <form id="packingForm" action="/upload_pakinglist_outcome" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="txtPacking" name="file" onchange="subirPacking()">
                    <input type="text" id="fileNumSalida" name="fileNumSalida">
                </form>
                <form id="packingDeleteForm" action="/delete_pakinglist_outcome/" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" id="fileDeleteNumSalida" name="fileDeleteNumSalida">
                </form>
            </div>
            @php
            if(isset($numero_de_salida))
            {
                $packinglist_path='/public/salidas/'.$numero_de_salida.'/packing_list/packing-list.pdf';
                if (Storage::exists($packinglist_path)) 
                {
                    echo "<br>";
                    echo "<div class='img_card col-lg-12' style='padding:10px'>";
                    echo "    <div class='img_card_top'>";
                    echo "        <h6><b>Packing list</b><button onclick='deletePacking()'><i class='fas fa-times'></i></button></h6>"; 
                    echo "    </div>";
                    echo "    <p><a href='/download_pakinglist_outcome/".$numero_de_salida."'><i class='fas fa-arrow-circle-down'></i></a><strong>Tamaño: </strong> ". round(Storage::size($packinglist_path)/1000000,2,PHP_ROUND_HALF_UP ) ." Mb</p>";
                    echo "</div>";
                }
            }
            @endphp
        </div>

        <div class="col-lg-10 controlDiv">
            <button type="button" class="btn btn-secondary" onclick="imgBtnClick()">Imagenes <i class="far fa-images"></i></button>
            <br>
            <div style="display:none">
                <form id="OutcomeImgForm" action="/upload_img_salida/" method="post" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" type="file" onchange="subirImagenes()" id="txtImagenes" name="filenames[]" multiple>
                    <input type="text" id="fileNumSalidaImg" name="fileNumSalidaImg">
                </form>
                <form id="OutcomeImgDeleteForm" action="/delete_img_salida/" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" id="ImgDeleteNumSalida" name="ImgDeleteNumSalida">
                    <input style="hidden" type="text" id="ImgNameDeleteNumSalida" name="ImgNameDeleteNumSalida">
                </form>
            </div>
            <br>

            @php
            if(isset($numero_de_salida))
            {
                $outcome_imgs_paths='public/salidas/'.$numero_de_salida.'/images/';
                $outcome_imgs = Storage::files($outcome_imgs_paths);
                foreach ($outcome_imgs as $outcome_img) 
                {
                    $img_file_name_array=explode('/',$outcome_img);

                    $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                    $img_file_url='storage/salidas/'.$numero_de_salida.'/images/'.$img_file_name;

                    echo "<div class='img_card col-lg-3'>";
                    echo "    <div class='img_card_top'>";
                    echo "        <h6><b>".$img_file_name."</b><button onclick='deleteImg(\"".$img_file_name."\")'><i class='fas fa-times'></i></button></h6>"; 
                    echo "    </div>";
                    echo "    <img src='".asset($img_file_url)."'>";
                    echo "</div>";
                }
            }
            @endphp
        </div>
    </div>   

    <div class="row" style="margin-top:20px;">
        <div class="col-lg-7 controlDiv"></div>
        <input type="button" class="col-lg-2 btn btn-success" onclick="guardarSalida()" value="Registrar" style="margin-right:20px;">

        <div class="btn-group col-lg-2" role="group">
            <button type="button" class="btn btn-outline-primary" onclick="downloadPDF()">Imprimir</button>
            <button type="button" class="btn btn-outline-primary">Terminar</button>
        </div>
    </div>   

    <h5 class="separtor">Partidas</h5>


    <h5 class="separtor"></h5>

</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>

function packingBtnClick()
{
    let NumSalida = $("#txtNumSalida").val();
    if(NumSalida.length != 9)
    {
        showModal("Alerta!","Primero guarde la salida.");
        return;
    }
    $('#txtPacking').click();
}
function imgBtnClick()
{
    let NumSalida = $("#txtNumSalida").val();
    if(NumSalida.length != 9)
    {
        showModal("Alerta!","Primero guarde la salida.");
        return;
    }
    $('#txtImagenes').click();
}
function subirPacking()
{
    let NumSalida = $("#txtNumSalida").val();
    if(NumSalida.length != 9)
    {
        showModal("Alerta!","Primero guarde la salida.");
        return;
    }
    
    $("#fileNumSalida").val(NumSalida);
    $("#packingForm").submit();
}
function subirImagenes()
{
    let NumSalida = $("#txtNumSalida").val();
    if(NumSalida.length != 9)
    {
        showModal("Alerta!","Primero guarde la salida.");
        return;
    }
    
    $("#fileNumSalidaImg").val(NumSalida);
    $("#OutcomeImgForm").submit();
}
function guardarSalida()
{
    //validaciones
    if($("#txtFecha").val().length < 1)
    {
        showModal("Alerta!", "Llene el campo fecha.");
        return;
    }
    if($("#txtCliente").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Cliente.");
        return;
    }
    if($("#txtTransportista").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Transportista.");
        return;
    }
    if($("#txtRegimen").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Regimen.");
        return;
    }
    //fin validaciones

    //document.getElementById("encabezadoForm").submit();
    $.ajax({
        method: 'POST',
        url: $("#encabezadoForm").attr("action"),
        data: $("#encabezadoForm").serialize(), 
        success: function(response) {
            if(response.numero_de_salida.length == 9)
            {
                showModal("Notificación","Registrado con exito: '"+response["numero_de_salida"]+"'");
                $("#txtNumSalida").val(response["numero_de_salida"]);
                //$("#outcomeID").val(response["id_salida"]);    
            } else
            {
                showModal("Notificación","Error: "+response+".");
            }
        },
    });
}

function deletePacking()
{
    let NumSalida = $("#txtNumSalida").val();
    if (confirm("Desea eliminar el packing list?"))
    {
        $("#fileDeleteNumSalida").val(NumSalida);
        $("#packingDeleteForm").submit();
    }
}
function deleteImg(img_name)
{
    let NumSalida = $("#txtNumSalida").val();
    if (confirm("Desea eliminar esta imagen?"))
    {
        $("#ImgDeleteNumSalida").val(NumSalida);
        $("#ImgNameDeleteNumSalida").val(img_name);
        $("#OutcomeImgDeleteForm").submit();
    }
}

</script>
@endsection