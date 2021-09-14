@extends('layouts.common')
@section('headers')
<style>
.overlay{
    opacity:0.8;
    background: rgb(142,142,142);
    background: radial-gradient(circle, rgba(142,142,142,1) 0%, rgba(24,24,24,0.8130602582830007) 100%);
    position:fixed;
    width:100%;
    height:100%;
    top:0px;
    left:0px;
    z-index:1000;
}
</style>
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear entrada.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

    <h5 class="separtor">Encabezado</h5>

    <form id="encabezadoForm" action="/int/entradas" method="post">
    @csrf

    <div class="row">
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Numero de Entrada:</label>
            <input type="text" class="form-control" id="txtNumEntrada" name="txtNumEntrada" value="{{ $numero_de_entrada ?? '' }}" readonly style="text-align:center;">       
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Fecha:</label>
            <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="@if (isset($income)){{ explode(' ',$income->cdate)[0] }}@endif" >
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Impo/Expo:{{ $income->impoExpo ?? '' }}</label>
            <select class="form-select" id = "txtImpoExpo" name = "txtImpoExpo">
                <option value=0></option>
                <option value="Impo" @php if(isset($income)){if($income->impoExpo == "Impo" ){echo "selected";}}@endphp >Impo</option>
                <option value="Expo" @php if(isset($income)){if($income->impoExpo == "Expo" ){echo "selected";}}@endphp >Expo</option>
                <option value="Impo-A1" @php if(isset($income)){if($income->impoExpo == "Impo-A1" ){echo "selected";}}@endphp >Impo-A1</option>
                <option value="Impo-AF" @php if(isset($income)){if($income->impoExpo == "Impo-AF" ){echo "selected";}}@endphp >Impo-AF</option>
                <option value="Impo-IN" @php if(isset($income)){if($income->impoExpo == "Impo-IN" ){echo "selected";}}@endphp >Impo-IN</option>
                <option value="Expo-RT" @php if(isset($income)){if($income->impoExpo == "Expo-RT" ){echo "selected";}}@endphp >Expo-RT</option>
                <option value="Dist" @php if(isset($income)){if($income->impoExpo == "Dist" ){echo "selected";}}@endphp >Dist</option>
            </select>
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Cliente:</label>
            <select class="form-select" id = "txtCliente" name = "txtCliente" onchange="checkCampoCliente()">
            <option value=0 selected></option>
            @foreach ($clientes as $clienteOp)
            <option value="{{ $clienteOp->id }}" @php if(isset($income)){if($income->customer_id === $clienteOp->id){echo "selected";}}@endphp >{{ $clienteOp->name }}</option>
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
            <option value="{{ $transportistaOp->id }}" @php if(isset($income)){if($income->carrier_id === $transportistaOp->id){echo "selected";}}@endphp >{{ $transportistaOp->name }}</option>
            @endforeach
            <option value = "-2" id="option_new_transportista" >(Crear nuevo +)</option>
            </select>
        </div>
        <div class="col-lg-3" >
            <label class="form-label">Proveedor:</label>
            <select class="form-select" id = "txtProveedor" name = "txtProveedor" onchange="agregarProveedor()">
            <option value=0 selected></option>
            @foreach ($proveedores as $proveedoresOp)
            <option value="{{ $proveedoresOp->id }}" @php if(isset($income)){if($income->supplier_id === $proveedoresOp->id){echo "selected";}}@endphp >{{ $proveedoresOp->name }}</option>
            @endforeach
            <option value = "-2" id="option_new_proveedor" >(Crear nuevo +)</option>
            </select>
        </div>
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Referencia:</label>
            <input type="text" class="form-control" id="txtReferencia" name="txtReferencia" value="{{ $income->reference ?? '' }}">       
        </div>
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Caja:</label>
            <input type="text" class="form-control" id="txtCaja" name="txtCaja" value="{{ $income->trailer ?? '' }}">       
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Sello:</label>
            <input type="text" class="form-control" id="txtSello" name="txtSello" value="{{ $income->seal ?? '' }}">       
        </div>
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Factura:</label>
            <input type="text" class="form-control" id="txtFactura" name="txtFactura" value="{{ $income->invoice ?? '' }}">       
        </div>
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Tracking:</label>
            <input type="text" class="form-control" id="txtTracking" name="txtTracking" value="{{ $income->tracking ?? '' }}">       
        </div>
        <div class="col-lg-3 controlDiv" style="">
            <label class="form-label">PO:</label>
            <input type="text" class="form-control" id="txtPO" name="txtPO" value="{{ $income->po ?? '' }}">       
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Actualizado por:</label>
            <input type="text" class="form-control" id="txtActualizadoPor" name="txtActualizadoPor" value="{{ $income->reviewed_by ?? '' }}">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="chkRev" id="chkRev" name="chkRev" @isset($income->reviewed){{ ($income->reviewed)?'checked':'' }}@endisset>
                <label class="form-check-label">revisado</label>
            </div>      
        </div>
        <div class="col-lg-2 controlDiv" >
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="chkUrgente" id="chkUrgente" name="chkUrgente" @isset($income->urgent){{ ($income->urgent)?'checked':'' }}@endisset>
                <label class="form-check-label">Urgente</label>
            </div>      
        </div>
        <div class="col-lg-2 controlDiv" >
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="chkOnhold" id="chkOnhold" name="chkOnhold" @isset($income->onhold){{ ($income->onhold)?'checked':'' }}@endisset>
                <label class="form-check-label">On hold</label>
            </div>      
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Clasificación:</label>
            <select class="form-select" id = "txtClasificacion" name = "txtClasificacion">
                <option value=0></option>
                <option value="Materia prima" @php if(isset($income)){if($income->type == "Materia prima" ){echo "selected";}}@endphp >Materia prima</option>
                <option value="Equipo" @php if(isset($income)){if($income->type == "Equipo" ){echo "selected";}}@endphp >Equipo</option>
            </select>
        </div>
        
    </div>

    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="2">{{ $income->observations ?? '' }}</textarea>
    </div>

    </form>

    <div class="row">

        <div class="col-lg-2 controlDiv" >

            <button type="button" class="btn btn-secondary" onclick="packingBtnClick()">Packing list <i class="far fa-file-alt"></i></button>
            <br>
            <div style="display:none">
                <form id="packingForm" action="/upload_pakinglist" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="txtPacking" name="file" onchange="subirPacking()">
                    <input type="text" id="fileNumEntrada" name="fileNumEntrada">
                </form>
                <form id="packingDeleteForm" action="/delete_pakinglist/" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" id="fileDeleteNumEntrada" name="fileDeleteNumEntrada">
                </form>
            </div>
            @php
            if(isset($numero_de_entrada))
            {
                $packinglist_path='/public/entradas/'.$numero_de_entrada.'/packing_list/packing-list.pdf';
                if (Storage::exists($packinglist_path)) 
                {
                    echo "<br>";
                    echo "<div class='img_card col-lg-12' style='padding:10px'>";
                    echo "    <div class='img_card_top'>";
                    echo "        <h6><b>Packing list</b><button onclick='deletePacking()'><i class='fas fa-times'></i></button></h6>"; 
                    echo "    </div>";
                    echo "    <p><a href='/download_pakinglist/".$numero_de_entrada."'><i class='fas fa-arrow-circle-down'></i></a><strong>Tamaño: </strong> ". round(Storage::size($packinglist_path)/1000000,2,PHP_ROUND_HALF_UP ) ." Mb</p>";
                    echo "</div>";
                }
            }
            @endphp
        </div>

        <div class="col-lg-10 controlDiv">
            <button type="button" class="btn btn-secondary" onclick="imgBtnClick()">Imagenes <i class="far fa-images"></i></button>
            <br>
            <div style="display:none">
                <form id="IncomeImgForm" action="/upload_img_entrada/" method="post" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" type="file" onchange="subirImagenes()" id="txtImagenes" name="filenames[]" multiple>
                    <input type="text" id="fileNumEntradaImg" name="fileNumEntradaImg">
                </form>
                <form id="IncomeImgDeleteForm" action="/delete_img_entrada/" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" id="ImgDeleteNumEntrada" name="ImgDeleteNumEntrada">
                    <input style="hidden" type="text" id="ImgNameDeleteNumEntrada" name="ImgNameDeleteNumEntrada">
                </form>
            </div>
            <br>

            @php
            if(isset($numero_de_entrada))
            {
                $income_imgs_paths='public/entradas/'.$numero_de_entrada.'/images/';
                $income_imgs = Storage::files($income_imgs_paths);
                foreach ($income_imgs as $income_img) 
                {
                    $img_file_name_array=explode('/',$income_img);

                    $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                    $img_file_url='storage/entradas/'.$numero_de_entrada.'/images/'.$img_file_name;

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
        <input type="button" class="col-lg-2 btn btn-success" onclick="guardarEntrada()" value="Registrar" style="margin-right:20px;">

        <div class="btn-group col-lg-2" role="group">
            <button type="button" class="btn btn-outline-primary" onclick="downloadPDF()">Imprimir</button>
            <button type="button" class="btn btn-outline-primary">Terminar</button>
        </div>
    </div>   

    <h5 class="separtor">Partidas</h5>

    <div class="row" style="margin-top:20px;">
        <div class="col-lg-1 controlDiv" style="text-align:center;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary" onclick="createPartida()">+</button>
            </div>
        </div>
        <div class="col-lg-10 controlDiv" style="overflow: auto; text-align:center;">
        <div class="btn-group me-2" id="div_btns_partidas" role="group">
        @php
        if(isset($income))
        {
            $income_row_index = 0;
            foreach ($income->income_rows as $income_row) 
            {
                $income_row_index++;
                echo "<button type='button' class='btn btn-outline-secondary btnIncomeRow' onclick='goPartida(this.id)' id='btnIncomeRow_".$income_row->id."'>".$income_row_index."</button>";
            }
        }
        @endphp
        </div>
        </div>
        <div class="col-lg-1 controlDiv" style="text-align:center;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-secondary" onclick="irMasiva()"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>
    </div>

    <h5 class="separtor"></h5>

    <form id="formIncomeRow" action="/income_row" method="post">
    @csrf
    <div class="row">
        <div class="col-lg-4 controlDiv" >
            <label class="form-label">Numero de parte:</label>
            <input type="text" class="form-control" id="txtNumeroDeParte" name="txtNumeroDeParte" value="" onfocusout="getPartNumberInfo()">   
            <input type="hidden" id="txtNumeroDeParteID" name="txtNumeroDeParteID">
            <input type="hidden" id="txtNumeroDePartePesoU" name="txtNumeroDePartePesoU">
            <input type="hidden" id="incomeID" name="incomeID" value="{{ $income->id ?? '' }}"> 
            <input type="hidden" id="incomeRowID" name="incomeRowID" value=""> 
        </div>
        <div class="col-lg-4 controlDiv" >
            <label class="form-label">Descripción Inglés:</label>
            <input type="text" class="form-control" id="txtDescIng" name="txtDescIng" value="">       
        </div>
        <div class="col-lg-4 controlDiv" >
            <label class="form-label">Descripción Español:</label>
            <input type="text" class="form-control" id="txtDescEsp" name="txtDescEsp" value="">       
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Cantidad:</label>
            <input type="number" class="form-control" id="txtCantidad" name="txtCantidad" value="" onchange="calcularPesoNeto()">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">UM:</label>
            <select class="form-select" id="txtUM" name="txtUM">
            @foreach ($unidades_de_medida as $unidade_de_medidaOp)
            <option value="{{ $unidade_de_medidaOp->desc }}">{{ $unidade_de_medidaOp->desc }}</option>
            @endforeach
            </select>
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Bultos:</label>
            <input type="number" class="form-control" id="txtBultos" name="txtBultos" value="" onchange="calcularPesoBruto()">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Tipo bulto:</label>
            <select class="form-select" id = "txtUMB" name="txtUMB" onchange="tipoBultoChange()">
            @foreach ($tipos_de_bulto as $tipos_de_bultoOp)
            <option value="{{ $tipos_de_bultoOp->desc }}">{{ $tipos_de_bultoOp->desc }}</option>
            @endforeach
            </select>
            <input type="hidden" id="txtUMBPeso">
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Peso neto:</label>
            <input type="number" class="form-control" id="txtPesoNeto" name="txtPesoNeto" value="" onchange="calcularPesoBruto()">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Peso bruto:</label>
            <input type="number" class="form-control" id="txtPesoBruto" name="txtPesoBruto" value="">       
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">País:</label>
            <input type="text" class="form-control" id="txtPais" name="txtPais" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">fracción:</label>
            <input type="text" class="form-control" id="txtFraccion" name="txtFraccion" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">nico:</label>
            <input type="text" class="form-control" id="txtNico" name="txtNico" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">PO:</label>
            <input type="text" class="form-control" id="txtPOPartida" name="txtPOPartida" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">locación:</label>
            <input type="text" class="form-control" id="txtLocacion" name="txtLocacion" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">IMMEX:</label>
            <input type="text" class="form-control" id="txtIMMEX" name="txtIMMEX" value="">       
        </div>
    </div>

    <div class="row">
    <div class="col-lg-2 controlDiv" >
            <label class="form-label">marca:</label>
            <input type="text" class="form-control" id="txtMarca" name="txtMarca" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">modelo:</label>
            <input type="text" class="form-control" id="txtModelo" name="txtModelo" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">serie:</label>
            <input type="text" class="form-control" id="txtSerie" name="txtSerie" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">lote:</label>
            <input type="text" class="form-control" id="txtLote" name="txtLote" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">regimen:</label>
            <input type="text" class="form-control" id="txtRegimen" name="txtRegimen" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">skids:</label>
            <input type="text" class="form-control" id="txtSkids" name="txtSkids" value="">       
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea class="form-control" id="txtObservacionesPartida" name="txtObservacionesPartida" rows="2"></textarea>
    </div>
    </form>

    <div id="fraccionAlert" class="alert alert-warning" role="alert" style="display:none">
    </div>

    <h5 class="separtor"></h5>

    <div class="row" style="margin-top:20px;">
        <div class="col-lg-9 controlDiv"></div>
        <input type="button" class="col-lg-1 btn btn-success " style="margin-right:20px;" value="Guardar" onclick="guardarPartida()">
        <input type="button" class="col-lg-1 btn btn-danger " value="Eliminar" onclick="eliminarPartida()">
    </div>  

</div>
</div>
</div>
</div>


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

@if (isset($part_number))
$("#txtNumeroDeParte").val("{{ $part_number->part_number }}");
getPartNumberInfo();
@endif


function irMasiva()
{
    let NumEntrada = $("#txtNumEntrada").val();
    if(NumEntrada.length != 9 || $("#incomeID").val().length < 1)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }
    location.href="/income_row_massive/"+NumEntrada;
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
    else
    {
        if($("#supplier_carrier_modLabel").html() == "Proveedor")
        {
            $.ajax({url: "/int/catalog/suppliers_add/"+$("#txtModal").val().trim(),context: document.body}).done(function(result) 
            {
                //location.reload();
                $("#option_new_proveedor").remove();
                $('#txtProveedor').append($('<option>', {
                    value: result["id"],
                    text: result["supplier"]
                }));
                $('#txtProveedor').val(result["id"]);
                closeSCmodal();
            });
        }
    }
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
function agregarProveedor()
{
    if ( $("#txtProveedor").val() == "-2")
    {
        $("#txtModal").val("");
        $("#supplier_carrier_modLabel").html("Proveedor");        
        $("#supplier_carrier_mod_back").show();
        $("#supplier_carrier_mod").show();
    }
}


function closeSCmodal()
{
    $("#supplier_carrier_mod_back").hide();
    $("#supplier_carrier_mod").hide();
    $("#txtModal").value("");
    $("#supplier_carrier_modLabel").value("");
}

function tipoBultoChange()
{
    let txtUMB = $("#txtUMB").val();
    var bultos_peso = {@foreach ($tipos_de_bulto as $tipos_de_bultoOp)@if(!$loop->first) , @endif"{{ $tipos_de_bultoOp->desc }}":{{ $tipos_de_bultoOp->weight }}@endforeach};
    for (var key in bultos_peso) 
    {
        if(key == txtUMB)
        {
            $("#txtUMBPeso").val(bultos_peso[key]);
            break;
        }
        // en caso de no encontrar nada el valor se pone a cero
        $("#txtUMBPeso").val(bultos_peso[key]);
    }
    calcularPesoBruto();
}

function calcularPesoNeto()
{
    let cantidad = Number($("#txtCantidad").val());
    let peso_unitario = Number($("#txtNumeroDePartePesoU").val());
    $("#txtPesoNeto").val(cantidad*peso_unitario);
    calcularPesoBruto();
}

function calcularPesoBruto()
{
    let peso_neto = Number($("#txtPesoNeto").val());
    let cantidad_bultos = Number($("#txtBultos").val());
    let peso_bulto = Number($("#txtUMBPeso").val());
    $("#txtPesoBruto").val(cantidad_bultos*peso_bulto+peso_neto);
}
function packingBtnClick()
{
    let NumEntrada = $("#txtNumEntrada").val();
    if(NumEntrada.length != 9)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }
    $('#txtPacking').click();
}
function imgBtnClick()
{
    let NumEntrada = $("#txtNumEntrada").val();
    if(NumEntrada.length != 9)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }
    $('#txtImagenes').click();
}
function subirPacking()
{
    let NumEntrada = $("#txtNumEntrada").val();
    if(NumEntrada.length != 9)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }
    
    $("#fileNumEntrada").val(NumEntrada);
    $("#packingForm").submit();
}
function subirImagenes()
{
    let NumEntrada = $("#txtNumEntrada").val();
    if(NumEntrada.length != 9)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }
    
    $("#fileNumEntradaImg").val(NumEntrada);
    $("#IncomeImgForm").submit();
}
function guardarEntrada()
{
    //validaciones
    if($("#txtFecha").val().length < 1)
    {
        showModal("Alerta!", "Llene el campo fecha.");
        return;
    }
    if($("#txtImpoExpo").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Impo/Expo.");
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
    if($("#txtProveedor").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Proveedor.");
        return;
    }
    //fin validaciones

    //document.getElementById("encabezadoForm").submit();
    $.ajax({
        method: 'POST',
        url: $("#encabezadoForm").attr("action"),
        data: $("#encabezadoForm").serialize(), 
        success: function(response) {
            if(response.numero_de_entrada.length == 9)
            {
                showModal("Notificación","Registrado con exito: '"+response["numero_de_entrada"]+"'");
                $("#txtNumEntrada").val(response["numero_de_entrada"]);
                $("#incomeID").val(response["id_entrada"]);    
            } else
            {
                showModal("Notificación","Error: "+response+".");
            }
        },
    });
}

function deletePacking()
{
    let NumEntrada = $("#txtNumEntrada").val();
    if (confirm("Desea eliminar el packing list?"))
    {
        $("#fileDeleteNumEntrada").val(NumEntrada);
        $("#packingDeleteForm").submit();
    }
}
function deleteImg(img_name)
{
    let NumEntrada = $("#txtNumEntrada").val();
    if (confirm("Desea eliminar esta imagen?"))
    {
        $("#ImgDeleteNumEntrada").val(NumEntrada);
        $("#ImgNameDeleteNumEntrada").val(img_name);
        $("#IncomeImgDeleteForm").submit();
    }
}

function getPartNumberInfo()
{
    let NumEntrada = $("#txtNumEntrada").val();
    if(NumEntrada.length != 9)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }

    if($('#txtNumeroDeParte').prop('readonly'))
    {
        return;
    }
    
    let numeroDeParte = $("#txtNumeroDeParte").val();
    let cliente = $("#txtCliente").val();
    $.ajax({url: "/part_number/"+numeroDeParte+"/"+cliente+"/get",context: document.body}).done(function(result) 
        {
            if(result.part_number == null)
            {
                if(confirm("El número de parte no existe, desea crearlo?"))
                {
                    //window.open('/part_number/' + numeroDeParte + '/' + cliente + '/' + NumEntrada + '/edit', '_blank').focus();
                    location.replace('/part_number/' + numeroDeParte + '/' + cliente + '/' + NumEntrada + '/edit');
                }
                else
                {
                    $('#txtNumeroDeParte').val("");
                    $('#txtNumeroDeParte').focus();
                }
                return;
            }
            fillPartidaFields(result);
            $("#incomeRowID").val("");
        });
}

function fillPartidaFields(data)
{
    //let NumEntrada = $("#txtNumEntrada").val();
    //if(NumEntrada.length != 9)
    //{
    //    showModal("Alerta!","Primero guarde la entrada.");
    //    return;
    //}
    //if($("#txtCliente").val() != data.customer_id)
    //{
    //    showModal("Validación","Este numero de parte no corresponde al cliente seleccionado!");
    //    return;
    //}

    $("#txtNumeroDeParte").val(data.part_number);
    $("#txtNumeroDeParteID").val(data.id);
    $("#txtNumeroDePartePesoU").val(data.unit_weight);
    $("#txtDescIng").val(data.desc_ing);
    $("#txtDescEsp").val(data.desc_esp);
    $("#txtCantidad").val(0);
    $("#txtUM").val(data.um);
    $("#txtBultos").val(0);
    $("#txtUMB").val("");
    $("#txtPesoNeto").val(0);
    $("#txtPesoBruto").val(0);
    $("#txtPais").val(data.origin_country);
    $("#txtFraccion").val(data.fraccion);
    $("#txtNico").val(data.nico);
    $("#txtPOPartida").val($("#txtPO").val());
    $("#txtIMMEX").val(data.imex);
    $("#txtMarca").val(data.brand);
    $("#txtModelo").val(data.model);
    $("#txtSerie").val(data.serial);
    $("#txtRegimen").val(data.regime);
    $("#txtObservacionesPartida").val("");
    if(data.fraccion_especial != "")
    {
        $("#fraccionAlert").show();
        $("#fraccionAlert").html(data.fraccion_especial);
    }
    else
    {
        $("#fraccionAlert").removeAttr("style").hide();
    }
    $('#txtNumeroDeParte').prop('readonly', false);
}

function createPartida()
{
    $(".btnIncomeRow").each(function(){
        $(this).removeClass("active");
    });
    $("#incomeRowID").val("");
    $("#txtNumeroDeParte").val("");
    $("#txtNumeroDeParteID").val("");
    $("#txtNumeroDePartePesoU").val("");
    $("#txtDescIng").val("");
    $("#txtDescEsp").val("");
    $("#txtCantidad").val("");
    $("#txtUM").val("");
    $("#txtBultos").val("");
    $("#txtUMB").val("");
    $("#txtUMBPeso").val("0");
    $("#txtPesoNeto").val("");
    $("#txtPesoBruto").val("");
    $("#txtPais").val("");
    $("#txtFraccion").val("");
    $("#txtNico").val("");
    $("#txtPOPartida").val("");
    $("#txtLocacion").val("");
    $("#txtIMMEX").val("");
    $("#txtMarca").val("");
    $("#txtModelo").val("");
    $("#txtSerie").val("");
    $("#txtLote").val("");
    $("#txtRegimen").val("");
    $("#txtSkids").val("");
    $("#txtPO").val("");
    $("#txtObservacionesPartida").val("");
    $("#fraccionAlert").removeAttr("style").hide();
    $("#fraccionAlert").html("");
    $('#txtNumeroDeParte').prop('readonly', false);
}

function goPartida(id)
{
    $(".btnIncomeRow").each(function(){
        $(this).removeClass("active");
    });
    $("#"+id).addClass("active");

    let income_row_id = id.split("_")[1];
    $.ajax({url: "/income_row/"+income_row_id,context: document.body}).done(function(response) 
        {
            $("#incomeRowID").val(response.income_row.id);
            $("#txtNumeroDeParte").val(response.part_number.part_number);
            $("#txtNumeroDeParteID").val(response.part_number.id);
            $("#txtNumeroDePartePesoU").val(response.part_number.unit_weight);
            $('#txtNumeroDeParte').prop('readonly', true);
            $("#txtDescIng").val(response.income_row.desc_ing);
            $("#txtDescEsp").val(response.income_row.desc_esp);
            $("#txtCantidad").val(response.income_row.units);
            $("#txtUM").val(response.income_row.ump);
            $("#txtBultos").val(response.income_row.bundles);
            $("#txtUMB").val(response.income_row.umb);
            $("#txtUMBPeso").val();
            $("#txtPesoNeto").val(response.income_row.net_weight);
            $("#txtPesoBruto").val(response.income_row.gross_weight);
            $("#txtPais").val(response.income_row.origin_country);
            $("#txtFraccion").val(response.income_row.fraccion);
            $("#txtNico").val(response.income_row.nico);
            $("#txtPOPartida").val(response.income_row.po);
            $("#txtLocacion").val(response.income_row.location);
            $("#txtIMMEX").val(response.income_row.imex);
            $("#txtMarca").val(response.income_row.brand);
            $("#txtModelo").val(response.income_row.model);
            $("#txtSerie").val(response.income_row.serial);
            $("#txtLote").val(response.income_row.lot);
            $("#txtRegimen").val(response.income_row.regime);
            $("#txtSkids").val(response.income_row.skids);
            $("#txtPO").val(response.income_row.po);
            $("#txtObservacionesPartida").val(response.income_row.observations);
            $("#fraccionAlert").html("");
            if(response.part_number.fraccion_especial != "")
            {
                $("#fraccionAlert").show();
                $("#fraccionAlert").html(response.part_number.fraccion_especial);
            }
            else
            {
                $("#fraccionAlert").removeAttr("style").hide();
            }
        });

}

function guardarPartida()
{
    if($("#txtNumEntrada").val().length != 9 || $("#incomeID").val().length < 1)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }
    if($("#txtNumeroDeParteID").val().length < 1)
    {
        showModal("Alerta!","Número de parte no valido.");
        return;
    }
    //$("#formIncomeRow").submit();
    
    $.ajax({
        method: 'POST',
        url: $("#formIncomeRow").attr("action"),
        data: $("#formIncomeRow").serialize(), 
        success: function(response) {
            showModal("Notificación","Registrado con exito: '"+response.msg+"'");
            if(!response.is_update)
            {
                let index_ultima_partida = 1;
                $(".btnIncomeRow").each(function(){
                    index_ultima_partida++;
                });
                $("#div_btns_partidas").html($("#div_btns_partidas").html()+"<button type='button' class='btn btn-outline-secondary btnIncomeRow' onclick='goPartida(this.id)' id='btnIncomeRow_"+response.id+"'>"+index_ultima_partida+"</button>");
                $("#btnIncomeRow_"+response.id).click();
            }
        },
    });
}

function eliminarPartida()
{
    if(!confirm("¿Desea eliminar la partida?"))
    {
        return;
    }
    let id_income_row = $("#incomeRowID").val();
    let token = $("[name='_token']").val();
    if(id_income_row != "")
    {

    $.ajax({url: "/income_row_has_outcomes/"+id_income_row,context: document.body}).done(function(response) 
        {
            if(response.length > 0)
            {
                showModal("Alerta!","Esta partida ya cuenta con salida(s): " + response + ".<br>Verifíque con su equipo.");
                return;
            }
            $.ajax(
            {
                url: "/income_row/"+id_income_row,
                type: 'DELETE',
                data: {
                    "_token": token,
                },
                success: function (){
                    showModal("Notificación","Partida Eliminada");
                    let index_ultima_partida = 1;
                    $(".btnIncomeRow").each(function(){
                        
                        $(this).html(index_ultima_partida);
                        if($(this).attr("id").split("_")[1] == id_income_row)
                        {
                            $(this).remove();
                        }
                        else
                        {
                            index_ultima_partida++;
                        }
                        // se corre la siguiente funcion para resetear todos los controles
                        createPartida();
                    });
                }
            });
        });
    }
}

function downloadPDF()
{
    let incomeID = $("#incomeID").val();
    if(incomeID.length < 1)
    {
        return;
    }
    window.open('/int/entradas/'+incomeID+'/download_pdf', '_blank').focus();
}

function checkCampoCliente()
{
    let NumEntrada = $("#txtNumEntrada").val();
    let income_id = $("#incomeID").val();
    if(NumEntrada.length != 9 || income_id.length < 1)
    {
        return;
    }

    $.ajax({url: "/int/entradas_can_change_customer/" + income_id,context: document.body}).done(function(response) 
        {
            if(response["has_rows"])
            {
                if($("#txtCliente").val() != response["original_customer"])
                {
                    showModal("Advertencia","No se puede cambiar el cliente porque la entrada ya cuenta con "+response["income_rows_count"]+" partidas.");
                    $("#txtCliente").val(response["original_customer"]);
                }
                
            }
        });
}

</script>
@endsection