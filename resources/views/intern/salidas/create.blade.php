@extends('layouts.common')
@section('headers')
<style>
    .oversized
    {
        min-width: 100px;
    }
</style>
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
<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

    <h5 class="separtor">Encabezado</h5>

    <form id="encabezadoForm" action="/int/salidas" method="post">
    <input type="hidden" class="outPost" name="_token" value="{{ csrf_token() }}" />

    <div class="row">
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Numero de Salida:</label>
            <input type="text" class="form-control" id="txtNumSalida" name="txtNumSalida" value="{{ $numero_de_salida ?? '' }}" readonly style="text-align:center;">       
            <input type="hidden" class="outPost" id="outcomeID" name="outcomeID" value="{{ $outcome->id ?? '' }}">
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Regimen:</label>
            <select class="form-select" id = "txtRegimen" name = "txtRegimen">
            <option value=0 selected></option>
            @foreach ($regimes as $regimeOp)
            <option value="{{ $regimeOp->name }}" @php if(isset($outcome)){if($outcome->regime == $regimeOp->name){echo "selected";}}@endphp >{{ $regimeOp->name }}</option>
            @endforeach
            </select>
        </div>
        

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Fecha:</label>
            <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="@if (isset($outcome)){{ explode(' ',$outcome->cdate)[0] }}@else{{date('Y-m-d')}}@endif">
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Cliente:</label>
            <select class="form-select" id = "txtCliente" name = "txtCliente" onchange="checkCampoCliente()">
            <option value=0 selected></option>
            @foreach ($clientes as $clienteOp)
            <option value="{{ $clienteOp->id }}" @php if(isset($outcome)){if($outcome->customer_id == $clienteOp->id){echo "selected";}}@endphp >{{ $clienteOp->name }}</option>
            @endforeach
            </select>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Transportista:</label>
            <select class="form-select" id = "txtTransportista" name = "txtTransportista" onchange="agregarTransportista()">
            <option value=0 selected></option>
            @foreach ($transportistas as $transportistaOp)
            <option value="{{ $transportistaOp->id }}" @php if(isset($outcome)){if($outcome->carrier_id == $transportistaOp->id){echo "selected";}}@endphp >{{ $transportistaOp->name }}</option>
            @endforeach
            <option value = "-2" id="option_new_transportista" >(Crear nuevo +)</option>
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
        
        <div class="col-lg-2 controlDiv" >
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

        <div class="col-lg-3 controlDiv" style="">
            <label class="form-label">Ubicación:</label>
            <input type="text" class="form-control" id="txtUbicacion" name="txtUbicacion" value="{{ $outcome->ubicacion ?? '' }}" list="listaUbicaciones">       
        </div>

        
    </div>

    <div class="row">

        <div class="col-lg-7 mb-3">
            <label class="form-label">Observaciones</label>
            <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="2">{{ $outcome->observations ?? '' }}</textarea>
        </div>

        <div class="col-lg-2 controlDiv" >
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="chkDescontar" id="chkDescontar" name="chkDescontar" @isset($outcome->discount){{ ($outcome->discount)?'checked':'' }}@endisset>
                <label class="form-check-label">Descontar</label>
            </div>      
        </div>

        <div class="col-lg-3 controlDiv" style="">
            <label class="form-label">Fecha y hora de despacho:</label>
            <input class="form-control" id="txtDiaHoraDespacho" name="txtDiaHoraDespacho" type="datetime-local" value="@if (isset($outcome->dtdespacho)){{ str_replace(' ','T',$outcome->dtdespacho) }}@endif">
        </div>

    </div>

    </form>

    <div class="row">

        <div class="col-lg-2 controlDiv" >

            <button type="button" class="btn btn-secondary" onclick="packingBtnClick()">Packing list <i class="far fa-file-alt"></i></button>
            <br>
            <div style="display:none">
                <form id="packingForm" action="/upload_pakinglist_outcome" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="txtPacking" name="files[]" onchange="subirPacking()" accept="application/pdf" multiple>
                    <input type="text" id="fileNumSalida" name="fileNumSalida">
                </form>
                <form id="packingDeleteForm" action="/delete_pakinglist_outcome" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" id="fileDeleteNumSalida" name="fileDeleteNumSalida">
                    <input type="hidden" id="fileDeleteNumSalida_filename" name="fileDeleteNumSalida_filename">
                </form>
            </div>
            @php
            if(isset($numero_de_salida))
            {
                $packinglist_path='/public/salidas/'.$numero_de_salida.'/packing_list/';
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
                        echo "        <h6><b>".$pck_file_name."</b><button onclick='deletePacking(\"".$pck_file_name."\")'><i class='fas fa-times'></i></button></h6>"; 
                        echo "    </div>";
                        echo "    <p><a href='/download_pakinglist_outcome/".$numero_de_salida."/".$pck_file_name."'><i class='fas fa-arrow-circle-down'></i></a><strong>Tamaño: </strong> ". round(Storage::size($packinglist)/1000000,2,PHP_ROUND_HALF_UP ) ." Mb</p>";
                        echo "</div>";
                    }
                }
            }
            @endphp
        </div>

        <div class="col-lg-10 controlDiv">
            <button type="button" class="btn btn-secondary" onclick="imgBtnClick()">Imagenes <i class="far fa-images"></i></button>
            <br>
            <div style="display:none">
                <form id="OutcomeImgForm" action="/upload_img_salida" method="post" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" type="file" onchange="subirImagenes()" accept="image/*" id="txtImagenes" name="filenames[]" multiple>
                    <input type="text" id="fileNumSalidaImg" name="fileNumSalidaImg">
                </form>
                <form id="OutcomeImgDeleteForm" action="/delete_img_salida" method="post" enctype="multipart/form-data">
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
        <div class="col-lg-6 controlDiv"></div>
        <input type="button" class="col-lg-2 btn btn-success" id="btnRegistrar" onclick="guardarSalida()" value="Registrar" style="margin-right:20px;">

        <div class="btn-group col-lg-2" role="group">
            <button type="button" class="btn btn-outline-primary" onclick="downloadPDF()">Imprimir</button>
            <button type="button" class="btn btn-outline-primary" id="btnTerminar" onclick="terminar()">Terminar</button>
        </div>

        <button type="button" class="col-lg-1 btn btn-primary" onclick="nuevaSalida()">Nueva <i class="fas fa-plus"></i></button>
    </div>   

    <h5 class="separtor">Partidas</h5>


    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                Inventario
            </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
            <div class="accordion-body">

                <div class="row">
                    <div class="col-lg-4 controlDiv" >
                        <label class="form-label">Rango:</label>
                        <div class="input-group">
                            <select class="form-select" id = "txtRangoInv" name = "txtRangoInv">
                                <option value=5>5 días</option>
                                <option value=15>15 días</option>
                                <option value=30>30 días</option>
                                <option value=90>90 días</option>
                                <option value=190>6 meses</option>
                                <option value=365>1 año</option>
                                <option value=1095>3 años</option>
                            </select>
                            <button type="button" class="btn btn-outline-secondary" onclick="consultarInventario()">Consultar</button>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-md-5" >
                        <br>
                        <button type="button" class="btn btn-success" onclick="guardarPartidas()">Guardar nuevas partidas</button>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-lg-10">
                    </div>
                    <div class="col-lg-2">
                        <input type="text" class="form-control" id="txtQuickSearch" placeholder="Busca rapida">       
                    </div>
                </div>
                <br>

                <div id = "tbl_inv" class="row">
                
                </div>

            </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                Partidas guardadas
            </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
            <div class="accordion-body">
                @if(isset($outcome)) 
                @php $outcome_rows_array = $outcome->outcome_rows; @endphp
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align:center">Entrada #</th>
                            <th scope="col" style="text-align:center">Número_de_parte</th>
                            <th scope="col" style="text-align:center">Piezas</th>
                            <th scope="col" style="text-align:center">Bultos</th>
                            <th scope="col" style="text-align:center">Peso_neto</th>
                            <th scope="col" style="text-align:center">Peso_bruto</th>
                            <th scope="col" style="text-align:center">PO</th>
                            <th scope="col" style="text-align:center">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($outcome_rows_array as $outcome_row_item) 
                        <tr id="outcome_tr_{{ $outcome_row_item->id }}">
                            <td style="text-align:center">{{ $outcome_row_item->income_row->income->getIncomeNumber() }}</td>
                            <td style="text-align:center">{{ $outcome_row_item->income_row->part_number()->part_number }}</td>
                            <td style="text-align:center">{{ $outcome_row_item->units }} {{ $outcome_row_item->ump }}</td>
                            <td style="text-align:center">{{ $outcome_row_item->bundles }} {{ $outcome_row_item->umb }}</td>
                            <td style="text-align:center">{{ $outcome_row_item->net_weight }}</td>
                            <td style="text-align:center">{{ $outcome_row_item->gross_weight }}</td>
                            <td style="text-align:center">{{ $outcome_row_item->income_row->po }}</td>
                            <td style="text-align:center"><button onclick="eliminarPartida({{ $outcome_row_item->id }})"><i class="fas fa-times" style="color:red"></i></button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br>
                <table class="table table-sm">
                <tr>
                    <td><strong> Peso Neto </strong></td>
                    <td><strong> Peso Bruto </strong></td>
                    <td><strong> Piezas UM </strong></td>
                    <td><strong> Bultos UM </strong></td>
                </tr>
                <tr>
                    <td id="tdPesoNeto">{{ $outcome->getPesoNeto() }}</td>
                    <td id="tdPesoBruto">{{ $outcome->getPesoBruto() }}</td>
                    <td id="tdPiezas">{!! str_replace("<br>","<br>",$outcome->getPiezasSum()) !!}</td>
                    <td id="tdBultos">{!! str_replace("<br>","<br>",$outcome->getBultosSum()) !!}</td>
                </tr>
            </table>
                @endif
            </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
</div>

<datalist id="listaUbicaciones">
<option>Yarda</option>
<option>Patio</option>
<option>Rampa</option>
<option>Almacen</option>
</datalist>



<!-- MODAL Transportista PROVEEDOR-->
<div id="supplier_carrier_mod_back" style="display:none" class="overlay" onclick="closeSCmodal()">
</div>
<div class="modal" tabindex="-1" role="dialog" id="supplier_carrier_mod" style="z-index:1001;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="supplier_carrier_modLabel" >Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeSCmodal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-lg-6 controlDiv" >
            <label class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="txtModal" value="">  
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="agregarSC()" >Agregar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeSCmodal()">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')
<script>

$(document).ready(function(){
    loadOC();
});

function loadOC()
{
    @if (isset($load_order))
    $.ajax({url: "/int/salidas_OC_load_rows/{{ $load_order->id }}",context: document.body}).done(function(result) 
        {
            $("#tbl_inv").html(result);
            $(".chkSingle").each(function(){
                $( this ).click();
            });

            
        });
    @else
    return;
    @endif
}

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
    $("#btnRegistrar").attr("disabled", true);
    $.ajax({
        method: 'POST',
        url: $("#encabezadoForm").attr("action"),
        data: $("#encabezadoForm").serialize(), 
        success: function(response) {
            if(response.numero_de_salida.length == 9)
            {
                showModal("Notificación","Registrado con exito: '"+response["numero_de_salida"]+"'");
                $("#txtNumSalida").val(response["numero_de_salida"]);
                $("#outcomeID").val(response["id_salida"]);  
                $("#btnRegistrar").attr("disabled", false);  
                @if (isset($load_order))
                    $.ajax({url: "/int/salidas_OC_set_status/{{ $load_order->id }}/"+response["numero_de_salida"],context: document.body}).done(function(result) 
                        {
                            showModal("Notificación","Registrado con exito: '"+response["numero_de_salida"]+"' asignada a OC-{{ $load_order->id }}");
                        });
                @endif
            } else
            {
                showModal("Notificación","Error: "+response+".");
                $("#btnRegistrar").attr("disabled", false);  
            }
        },
    });
}

function deletePacking(filename)
{
    
    let NumSalida = $("#txtNumSalida").val();
    if (confirm("Desea eliminar el packing list?"))
    {
        $("#fileDeleteNumSalida").val(NumSalida);
        $("#fileDeleteNumSalida_filename").val(filename);
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

function consultarInventario()
{
    let NumSalida = $("#txtNumSalida").val();
    let OutcomeID = $("#outcomeID").val();
    let cliente = $("#txtCliente").val();
    let rango = $("#txtRangoInv").val();
    //
    if(NumSalida.length != 9 || OutcomeID == "" || OutcomeID == "0")
    {
        showModal("Alerta!","Primero guarde la salida.");
        return;
    }
    if(cliente == 0)
    {
        showModal("Alerta!", "Llene el campo Cliente.");
        return;
    }
    $("#tbl_inv").html("Cargando ");
    $.ajax({url: "/int/inventory/"+cliente+"/"+rango,context: document.body}).done(function(result) 
        {
            $("#tbl_inv").html(result);
        });
}

function selectRow(row_id,index)
{
    var check = !$("#btncheck_"+row_id+"_"+index).prop('checked'); 
    $("#txtCantidad_"+row_id+"_"+index).prop('readonly', check);
    $("#txtBultos_"+row_id+"_"+index).prop('readonly', check);
    $("#txtUMB_"+row_id+"_"+index).prop('disabled', check);
    $("#txtPesoNeto_"+row_id+"_"+index).prop('readonly', check);
    $("#txtPesoBruto_"+row_id+"_"+index).prop('readonly', check);
    if(!check)
    {
        $("#inv_row_"+row_id+"_"+index).addClass("table-warning");
    }
    else
    {
        $("#inv_row_"+row_id+"_"+index).removeClass("table-warning");
    }
}
function selectGroup(control,income_id)
{
    $(".income_"+income_id).each(function(){
        if (!$(this).prop('checked'))
        {
            $(this).click();
        }
    });

    if(!$(control).prop('checked'))
    {
        $(".income_"+income_id).each(function(){
            $(this).click();
        });
    }
}

function guardarPartidas()
{
    let NumSalida = $("#txtNumSalida").val();
    let OutcomeID = $("#outcomeID").val();
    let cliente = $("#txtCliente").val();
    //
    if(NumSalida.length != 9 || OutcomeID == "" || OutcomeID == "0")
    {
        showModal("Alerta!","Primero guarde la salida.");
        return;
    }
    if(cliente == 0)
    {
        showModal("Alerta!", "Llene el campo Cliente.");
        return;
    }
    if(!confirm("Desea guardar las partidas seleccionadas? la página se refrescará al terminar para re-calcular inventario."))
    {
        return;
    }
    $(".chkSingle").each(function(){
        if ($(this).prop('checked'))
        {
            var current_row_id = $(this).attr('id').split("_")[1]; 
            var current_index = $(this).attr('id').split("_")[2];
            //outcomeID  <- ya contiene la clase
            $("#txtIncomeRowId_"+current_row_id+"_"+current_index).addClass("outPost");
            $("#txtCantidad_"+current_row_id+"_"+current_index).addClass("outPost");
            $("#txtUM_"+current_row_id+"_"+current_index).addClass("outPost");
            $("#txtBultos_"+current_row_id+"_"+current_index).addClass("outPost");
            $("#txtUMB_"+current_row_id+"_"+current_index).addClass("outPost");
            $("#txtPesoNeto_"+current_row_id+"_"+current_index).addClass("outPost");
            $("#txtPesoBruto_"+current_row_id+"_"+current_index).addClass("outPost");
        }
    });
    $.ajax({
        method: 'POST',
        url: "/outcome_row",
        data: $(".outPost").serialize(), 
        success: function(response) {
            showModal("Notificación","Se registraron " + response.length + " partidas.");
            window.location.replace("/int/salidas/"+NumSalida);
        },
    });

}

function validarCantidad(control,max,index)
{
    if(control.value > max)
    {
        showModal("Precaución!","Esta partida tiene un máximo de <strong>'" + max + "'</strong> unidades, verifíque la cantidad.");
        control.value = max;
        return;
    }
    if(control.value < 0)
    {
        control.value = 0;
        return;
    }
    
    let row_id = control.id.split("_")[1];
    calcularPesoNeto(row_id,index);
}

function eliminarPartida(outcome_row_id)
{
    if(!confirm("Desea eliminar la partida?"))
    {
        return;
    }
    $.ajax({url: "/outcome_row_delete/"+outcome_row_id,context: document.body}).done(function(result) 
        {
            $("#outcome_tr_"+outcome_row_id).remove();
            //limpiamos el inventario para forzar a que el usuario lo consulte de nuevo
            $("#tbl_inv").html("");
        });
}

function checkCampoCliente()
{
    @if (isset($load_order))
    if({{ $outcome->customer_id }} != $("#txtCliente").val())
        {
            showModal("Advertencia", "No se puede cambiar el cliente de una orden de carga.");
            $("#txtCliente").val({{ $outcome->customer_id }});
            return;
        }
    @endif

    let NumSalida = $("#txtNumSalida").val();
    let outcome_id = $("#outcomeID").val();
    if(NumSalida.length != 9 || outcome_id.length < 1)
    {
        return;
    }

    $.ajax({url: "/int/salidas_can_change_customer/" + outcome_id,context: document.body}).done(function(response) 
        {
            if(response["has_rows"])
            {
                if($("#txtCliente").val() != response["original_customer"])
                {
                    showModal("Advertencia","No se puede cambiar el cliente porque la salida ya cuenta con "+response["outcome_rows_count"]+" partidas.");
                    $("#txtCliente").val(response["original_customer"]);
                }
                
            }
        });
}

function tipoBultoChange(row_id,index)
{
    let txtUMB = $("#txtUMB_"+row_id+"_"+index).val();
    var bultos_peso = {@foreach ($tipos_de_bulto as $tipos_de_bultoOp)@if(!$loop->first) , @endif"{{ $tipos_de_bultoOp->desc }}":{{ $tipos_de_bultoOp->weight }}@endforeach};
    for (var key in bultos_peso) 
    {
        if(key == txtUMB)
        {
            $("#txtUMBPeso_"+row_id+"_"+index).val(bultos_peso[key]);
            break;
        }
        // en caso de no encontrar nada el valor se pone a cero
        $("#txtUMBPeso_"+row_id+"_"+index).val(bultos_peso[key]);
    }
    calcularPesoBruto(row_id,index);
}

function calcularPesoNeto(row_id,index)
{
    let cantidad = Number($("#txtCantidad_"+row_id+"_"+index).val());
    let peso_unitario = Number($("#txtNumeroDePartePesoU_"+row_id+"_"+index).val());
    $("#txtPesoNeto_"+row_id+"_"+index).val(cantidad*peso_unitario);
    calcularPesoBruto(row_id,index);
}

function calcularPesoBruto(row_id,index)
{
    let peso_neto = Number($("#txtPesoNeto_"+row_id+"_"+index).val());
    let cantidad_bultos = Number($("#txtBultos_"+row_id+"_"+index).val());
    let peso_bulto = Number($("#txtUMBPeso_"+row_id+"_"+index).val());
    $("#txtPesoBruto_"+row_id+"_"+index).val(cantidad_bultos*peso_bulto+peso_neto);
}

function downloadPDF()
{
    let OutcomeID = $("#outcomeID").val();
    if(OutcomeID.length < 1)
    {
        return;
    }
    window.open('/int/salidas/'+OutcomeID+'/download_pdf', '_blank').focus();
}

function terminar()
{
    let NumSalida = $("#txtNumSalida").val();
    let outcome_id = $("#outcomeID").val();
    if(NumSalida.length != 9 || outcome_id.length < 1)
    {
        return;
    }
    if(!confirm("¿Desea enviar la salida por correo?"))
    {
        return;
    }
    location.href = "/sendemail/"+outcome_id+"/salida";
    // $("#btnTerminar").prop("disabled",true);
    

    // $.ajax({url: "/sendemail/"+outcome_id+"/salida",context: document.body}).done(function(response) 
    //     {
    //         showModal("Notificación", "Enviada: " + NumSalida);
    //         $("#btnTerminar").prop("disabled",false);
    //     });
}


function agregarTransportista()
{
    if ( $("#txtTransportista").val() == "-2")
    {
        $("#txtModal").val("");
        $("#supplier_carrier_modLabel").html("Transportista");        
        $("#supplier_carrier_mod_back").show();
        $("#supplier_carrier_mod").show();
    }
}

function agregarSC()
{
    if($("#txtModal").val().trim() == "")
    {
        return;
    }
    if($("#supplier_carrier_modLabel").html() == "Transportista")
    {
        $.ajax({url: "/int/catalog/carriers_add/"+$("#txtModal").val().trim(),context: document.body}).done(function(result) 
        {
            //location.reload();
            $("#option_new_transportista").remove();
            $('#txtTransportista').append($('<option>', {
                value: result["id"],
                text: result["carrier"]
            }));
            $('#txtTransportista').val(result["id"]);
            closeSCmodal();
        });
    }
}

function closeSCmodal()
{
    $("#supplier_carrier_mod_back").hide();
    $("#supplier_carrier_mod").hide();
    $("#txtModal").val("");
    $("#supplier_carrier_modLabel").val("");
}

function nuevaSalida()
{
    if(!confirm("Desea crear una nueva Salida? los datos no guardados serán descartados."))
    {
        return;
    }
    location.href='/int/salidas/create';
}

function descontarDateTime()
{
    let element = document.getElementById("txtDiaHoraDespacho");
    
    if($("#chkDescontar").prop('checked'))
    {
        $("#txtDiaHoraDespacho").prop('disabled',false);

        var today = new Date();
        var hours = today.getHours();
        if(today.getHours() < 10)
        {
            hours = "0"+today.getHours();
        }
        var minutes = today.getMinutes();
        if(today.getMinutes() < 10)
        {
            minutes = "0"+today.getMinutes();
        }
        var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+'T'+hours+':'+minutes;
        $("#txtDiaHoraDespacho").val(date);
    }
    else
    {
        $("#txtDiaHoraDespacho").prop('disabled',true);
    }
}

$(document).ready(function(){
  $("#txtQuickSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tbl_inv tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

</script>
@endsection