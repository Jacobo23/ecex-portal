@extends('layouts.common')
@section('headers')
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
            <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="@if (isset($income)){{ explode(' ',$income->cdate)[0] }}@endif">
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
            <select class="form-select" id = "txtCliente" name = "txtCliente">
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
            <select class="form-select" id = "txtTransportista" name = "txtTransportista">
            <option value=0 selected></option>
            @foreach ($transportistas as $transportistaOp)
            <option value="{{ $transportistaOp->id }}" @php if(isset($income)){if($income->carrier_id === $transportistaOp->id){echo "selected";}}@endphp >{{ $transportistaOp->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="col-lg-3" >
            <label class="form-label">Proveedor:</label>
            <select class="form-select" id = "txtProveedor" name = "txtProveedor">
            <option value=0 selected></option>
            @foreach ($proveedores as $proveedoresOp)
            <option value="{{ $proveedoresOp->id }}" @php if(isset($income)){if($income->supplier_id === $proveedoresOp->id){echo "selected";}}@endphp >{{ $proveedoresOp->name }}</option>
            @endforeach
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

            <button type="button" class="btn btn-secondary" onclick="$('#txtPacking').click()">Packing list <i class="far fa-file-alt"></i></button>
            <br>
            <div style="display:none">
                <form id="packingForm" action="/upload_pakinglist" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="txtPacking" name="file" onchange="subirPacking()">
                    <input type="text" id="fileNumEntrada" name="fileNumEntrada">
                </form>
            </div>
            @php
            $packinglist_path='/entradas/'.$numero_de_entrada.'/packing_list/packing-list.pdf';
            if (Storage::exists($packinglist_path)) {
            echo "<br>";
            echo "<div>";
            echo "    <h5>Archivo:</h5>";
            echo "    <p><strong>Tamaño: </strong> ". Storage::size($packinglist_path)/1000000 ." Mb</p>";
            echo "</div>";
            }@endphp
        </div>

        <div class="col-lg-4 controlDiv" style="border: 1px solid black;">
            <button type="button" class="btn btn-secondary" onclick="$('#txtImagenes').click()">Imagenes <i class="far fa-images"></i></button>
            <br>
            <div style="display:none">
                <form id="IncomeImgForm" action="/upload_img_entrada/" method="post" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" type="file" onchange="subirImagenes()" id="txtImagenes" name="filenames[]" multiple>
                    <input type="text" id="fileNumEntradaImg" name="fileNumEntradaImg">
                </form>
            </div>

            @php
            $income_imgs_paths='/entradas/'.$numero_de_entrada.'/images/';
            $income_imgs = Storage::files($income_imgs_paths);
            foreach ($income_imgs as $income_img) 
            {
                //echo "<div class='divFile'>";
                //echo asset('storage/entradas/202100010/images/1628238110_1.jpg');
                //echo public_path();
                //echo "<img src=\"{{ url('storage/app/entradas/202100010/images/1628238110_1.jpg') }}\"/>";
                //echo "<img src='../storage/app/entradas/202100010/images/1628238110_1.jpg'>";
                echo "<img src='".asset('/storage/imagen_test.jpg')."'>";
                //echo "</div>";
            }
            @endphp
        </div>

    </div>   

    <div class="row" style="margin-top:20px;">
        <div class="col-lg-7 controlDiv"></div>
        <input type="button" class="col-lg-2 btn btn-success" onclick="guardarEntrada()" value="Registrar" style="margin-right:20px;">

        <div class="btn-group col-lg-2" role="group">
            <button type="button" class="btn btn-outline-primary">Imprimir</button>
            <button type="button" class="btn btn-outline-primary">Terminar</button>
        </div>
    </div>   

    <h5 class="separtor">Partidas</h5>

    <div class="row" style="margin-top:20px;">
        <div class="col-lg-1 controlDiv" style="text-align:center;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary">+</button>
            </div>
        </div>
        <div class="col-lg-10 controlDiv" style="overflow: auto; text-align:center;">
        <div class="btn-group me-2" role="group" style="">
            <button type="button" class="btn btn-outline-secondary">1</button>
            <button type="button" class="btn btn-outline-secondary">2</button>
            <button type="button" class="btn btn-outline-secondary">3</button>
            <button type="button" class="btn btn-outline-secondary">4</button>
        </div>
        </div>
        <div class="col-lg-1 controlDiv" style="text-align:center;">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-secondary"><i class="fas fa-ellipsis-h"></i></button>
            </div>
        </div>
    </div>

    <h5 class="separtor"></h5>

    <div class="row">
        <div class="col-lg-4 controlDiv" >
            <label class="form-label">Numero de parte:</label>
            <input type="text" class="form-control" id="txtNumeroDeParte" value="">       
        </div>
        <div class="col-lg-4 controlDiv" >
            <label class="form-label">Descripción Inglés:</label>
            <input type="text" class="form-control" id="txtDescIng" value="">       
        </div>
        <div class="col-lg-4 controlDiv" >
            <label class="form-label">Descripción Español:</label>
            <input type="text" class="form-control" id="txtDescEsp" value="">       
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Cantidad:</label>
            <input type="number" class="form-control" id="txtCantidad" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">UM:</label>
            <select class="form-select" id = "txtUM">
            @foreach ($unidades_de_medida as $unidade_de_medidaOp)
            <option value="{{ $unidade_de_medidaOp->desc }}">{{ $unidade_de_medidaOp->desc }}</option>
            @endforeach
            </select>
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Bultos:</label>
            <input type="number" class="form-control" id="txtBultos" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Tipo bulto:</label>
            <select class="form-select" id = "txtUMB">
            @foreach ($tipos_de_bulto as $tipos_de_bultoOp)
            <option value="{{ $tipos_de_bultoOp->desc }}">{{ $tipos_de_bultoOp->desc }}</option>
            @endforeach
            </select>
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Peso neto:</label>
            <input type="number" class="form-control" id="txtPesoNeto" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Peso bruto:</label>
            <input type="number" class="form-control" id="txtPesoBruto" value="">       
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">País:</label>
            <input type="text" class="form-control" id="txtPais" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">fracción:</label>
            <input type="text" class="form-control" id="txtFraccion" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">nico:</label>
            <input type="text" class="form-control" id="txtNico" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">PO:</label>
            <input type="text" class="form-control" id="txtPO" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">locación:</label>
            <input type="text" class="form-control" id="txtIMMEX" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">IMMEX:</label>
            <input type="text" class="form-control" id="txtIMMEX" value="">       
        </div>
    </div>

    <div class="row">
    <div class="col-lg-2 controlDiv" >
            <label class="form-label">marca:</label>
            <input type="text" class="form-control" id="txtMarca" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">modelo:</label>
            <input type="text" class="form-control" id="txtModelo" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">serie:</label>
            <input type="text" class="form-control" id="txtSerie" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">lote:</label>
            <input type="text" class="form-control" id="txtPO" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">regimen:</label>
            <input type="text" class="form-control" id="txtRegimen" value="">       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">skids:</label>
            <input type="text" class="form-control" id="txtPO" value="">       
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea class="form-control" id="txtObservacionesPartida" rows="2"></textarea>
    </div>

    <h5 class="separtor"></h5>

    <div class="row" style="margin-top:20px;">
        <div class="col-lg-9 controlDiv"></div>
        <input type="button" class="col-lg-1 btn btn-success " style="margin-right:20px;" value="Guardar">
        <input type="button" class="col-lg-1 btn btn-danger " value="Eliminar">
    </div>  



</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>
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
            if(response.length == 9)
            {
                showModal("Notificación","Registrado con exito: '"+response+"'");
                $("#txtNumEntrada").val(response);
    
            } else
            {
                showModal("Notificación","Error: "+response+".");
            }
        },
    });
}

</script>
@endsection