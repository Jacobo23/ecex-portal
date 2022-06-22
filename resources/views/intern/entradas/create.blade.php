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
            <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="@if (isset($income)){{ explode(' ',$income->cdate)[0] }}@else{{date('Y-m-d')}}@endif">
        </div>

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Impo/Expo:</label>
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
            <option value="{{ $clienteOp->id }}" @php if(isset($income)){if($income->customer_id == $clienteOp->id){echo "selected";}}@endphp >{{ $clienteOp->name }}</option>
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
            <option value="{{ $transportistaOp->id }}" @php if(isset($income)){if($income->carrier_id == $transportistaOp->id){echo "selected";}}@endphp >{{ $transportistaOp->name }}</option>
            @endforeach
            <option value = "-2" id="option_new_transportista" >(Crear nuevo +)</option>
            </select>
        </div>
        <div class="col-lg-3" >
            <label class="form-label">Proveedor:</label>
            <select class="form-select" id = "txtProveedor" name = "txtProveedor" onchange="agregarProveedor()">
            <option value=0 selected></option>
            @foreach ($proveedores as $proveedoresOp)
            <option value="{{ $proveedoresOp->id }}" @php if(isset($income)){if($income->supplier_id == $proveedoresOp->id){echo "selected";}}@endphp >{{ $proveedoresOp->name }}</option>
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

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Clasificación:</label>
            <select class="form-select" id = "txtClasificacion" name = "txtClasificacion">
                <option value=0></option>
                <option value="Materia prima" @php if(isset($income)){if($income->type == "Materia prima" ){echo "selected";}}@endphp >Materia prima</option>
                <option value="Equipo" @php if(isset($income)){if($income->type == "Equipo" ){echo "selected";}}@endphp >Equipo</option>
            </select>
        </div>

        <div class="col-lg-3 controlDiv" style="">
            <label class="form-label">Ubicación:</label>
            <input type="text" class="form-control" id="txtUbicacion" name="txtUbicacion" value="{{ $income->ubicacion ?? '' }}" list="listaUbicaciones">       
        </div>

        <div class="col-lg-3 controlDiv" >
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="chkRev" id="chkRev" name="chkRev" @isset($income->reviewed){{ ($income->reviewed)?'checked':'' }}@endisset>
                <label class="form-check-label">Revisado</label>
            </div>      
        </div>
        
    </div>

    <div class="row">

        <div class="mb-3 col-lg-9">
            <label class="form-label">Observaciones</label>
            <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="2">{{ $income->observations ?? '' }}</textarea>
        </div>

        <div class="col-lg-1 controlDiv" >
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

    </div>

    </form>

    <div class="row">

        <div class="col-lg-2 controlDiv" >

            <button type="button" class="btn btn-secondary" onclick="packingBtnClick()">Packing list <i class="far fa-file-alt"></i></button>
            <br>
            <div style="display:none">
                <form id="packingForm" action="/upload_pakinglist" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="txtPacking" name="file" onchange="subirPacking()" accept="application/pdf">
                    <input type="text" id="fileNumEntrada" name="fileNumEntrada">
                </form>
                <form id="packingDeleteForm" action="/delete_pakinglist" method="post" enctype="multipart/form-data">
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
                <form id="IncomeImgForm" action="/upload_img_entrada" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" type="file" onchange="subirImagenes()" accept="image/*" id="txtImagenes" name="filenames[]" multiple>
                    <input type="text" id="fileNumEntradaImg" name="fileNumEntradaImg">
                    <button type="submit" form="IncomeImgForm" value="Submit" id="sbtIncomeImgForm">Submit</button>
                    
                </form>
                <form id="IncomeImgDeleteForm" action="/delete_img_entrada" method="post" enctype="multipart/form-data">
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
        <div class="col-lg-6 controlDiv"></div>
        <input id="btnRegistrar" type="button" class="col-lg-2 btn btn-success" onclick="guardarEntrada()" value="Registrar" style="margin-right:20px;">

        <div class="btn-group col-lg-2" role="group">
            <button type="button" class="btn btn-outline-primary" onclick="downloadPDF()">Imprimir</button>
            <button type="button" class="btn btn-outline-primary" id="btnTerminar" onclick="terminar()">Terminar</button>
        </div>
        <button type="button" class="col-lg-1 btn btn-primary" onclick="nuevaEntrada()">Nueva <i class="fas fa-plus"></i></button>
        
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
            <!-- <input type="hidden" id="txtNumeroDePartePesoU" name="txtNumeroDePartePesoU"> -->
            <input type="hidden" id="incomeID" name="incomeID" value="{{ $income->id ?? '' }}"> 
            <input type="hidden" id="incomeRowID" name="incomeRowID" value=""> 
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Peso unitario:</label>
            <input type="number" class="form-control" id="txtNumeroDePartePesoU" name="txtNumeroDePartePesoU" value="" min=0 readonly>       
        </div>
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Actualizado:</label>
            <p id="lblActualizado"></p>      
        </div>

        @if (Auth::user()->canEditPartNumber())
        <div class="col-lg-2 controlDiv" >
            <input type="button" class="btn btn-light" onclick="actualizarNP()" value="Actualizar número de parte" style="position:relative; top:30px; color:#38b581;">
        </div>
        @endif


    </div>
    <div class="row">
        

        <div class="col-lg-6 controlDiv" >
            <label class="form-label">Descripción Inglés:</label>
            <input type="text" class="form-control" id="txtDescIng" name="txtDescIng" value="">       
        </div>
        <div class="col-lg-6 controlDiv" >
            <label class="form-label">Descripción Español:</label>
            <input type="text" class="form-control" id="txtDescEsp" name="txtDescEsp" value="">       
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 controlDiv" >
            <label class="form-label">Cantidad:</label>
            <input type="number" class="form-control" id="txtCantidad" name="txtCantidad" value="" min=0 onchange="calcularPesoNeto()">       
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
            <input type="number" class="form-control" id="txtBultos" name="txtBultos" value="" min=0 onchange="calcularPesoBruto()">       
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
            <input type="text" class="form-control" id="txtPais" name="txtPais" value="" maxlength="5" list="listaPaises">       
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
        <button type="button" class="col-lg-2 btn btn-light" style="margin-right:20px" onclick="GuardarRevisionPendiente()">Imprimir etiquetas <i class="fa-solid fa-print"></i></button>
        <div class="col-lg-6 controlDiv"></div>
        <input type="button" class="col-lg-2 btn btn-success " style="margin-right:20px;" value="Guardar" onclick="guardarPartida()">
        <input type="button" class="col-lg-1 btn btn-danger " value="Eliminar" onclick="eliminarPartida()">
    </div>  

     

</div>
</div>
</div>
</div>


<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

<div class="row" style="margin-top:20px;">
        <div class="col-lg-6 controlDiv">
            <table class="table table-sm">
                <tr>
                    <td><strong> Peso Neto </strong></td>
                    <td><strong> Peso Bruto </strong></td>
                    <td><strong> Piezas UM </strong></td>
                    <td><strong> Bultos UM </strong></td>
                </tr>
                <tr>
                    <td id="tdPesoNeto">0</td>
                    <td id="tdPesoBruto">0</td>
                    <td id="tdPiezas">0</td>
                    <td id="tdBultos">0</td>
                </tr>
            </table>
        </div>
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

<form id="frmEditNp" action="" method="post">
    @csrf
    <input type="hidden" id="txtNP_post" name="partNumber">
</form>


<datalist id="listaUbicaciones">
<option>Yarda</option>
<option>Patio</option>
<option>Rampa</option>
<option>Almacen</option>
</datalist>

<datalist id="listaPaises">
<option value='AF'>AFGANISTAN</option>
<option value='AL'>ALBANIA</option>
<option value='DE'>ALEMANIA</option>
<option value='AD'>ANDORRA</option>
<option value='AO'>ANGOLA</option>
<option value='AI'>ANGUILA</option>
<option value='AG'>ANTIGUA Y BARBUDA</option>
<option value='1'>ANTILLAS N.</option>
<option value='SA'>ARABIA S.</option>
<option value='2'>ARGELIA</option>
<option value='AR'>ARGENTINA</option>
<option value='AM'>ARMENIA</option>
<option value='AW'>ARUBA</option>
<option value='AU'>AUSTRALIA</option>
<option value='AT'>AUSTRIA</option>
<option value='AZ'>AZERBAIJAN</option>
<option value='BS'>BAHAMAS</option>
<option value='BH'>BAHREIN</option>
<option value='BD'>BANGLADESH</option>
<option value='BB'>BARBADOS</option>
<option value='BE'>BELGICA</option>
<option value='BZ'>BELICE</option>
<option value='BJ'>BENIN</option>
<option value='BM'>BERMUDAS</option>
<option value='8'>BIELORRUSIA</option>
<option value='BO'>BOLIVIA</option>
<option value='BA'>BOSNIA Y H.</option>
<option value='BW'>BOTSWANA</option>
<option value='BR'>BRASIL</option>
<option value='BN'>BRUNEI</option>
<option value='BG'>BULGARIA</option>
<option value='BF'>B. FASO</option>
<option value='BI'>BURUNDI</option>
<option value='3'>BUTAN</option>
<option value='CV'>C. VERDE</option>
<option value='KY'>CAIMAN</option>
<option value='4'>CAMBOYA</option>
<option value='CM'>CAMERUN</option>
<option value='CA'>CANADA</option>
<option value='5'>I. DEL CANAL</option>
<option value='TD'>CHAD</option>
<option value='CL'>CHILE</option>
<option value='CN'>CHINA</option>
<option value='CY'>CHIPRE</option>
<option value='VA'>VATICANO</option>
<option value='CC'>COCOS</option>
<option value='CO'>COLOMBIA</option>
<option value='KM'>COMORAS</option>
<option value='EU'>U. EUROPEA</option>
<option value='CG'>CONGO</option>
<option value='CK'>COOK</option>
<option value='KR'>COREA</option>
<option value='KP'>COREA</option>
<option value='CI'>C. DE MARFIL</option>
<option value='CR'>COSTA RICA</option>
<option value='HR'>CROACIA</option>
<option value='CU'>CUBA</option>
<option value='9'>CURAZAO</option>
<option value='DK'>DINAMARCA</option>
<option value='DJ'>DJIBOUTI</option>
<option value='DM'>DOMINICA</option>
<option value='EC'>ECUADOR</option>
<option value='EG'>EGIPTO</option>
<option value='SV'>EL SALVADOR</option>
<option value='12'>E. ARABES U.</option>
<option value='11'>ERITREA</option>
<option value='SK'>ESLOVAQUIA</option>
<option value='SI'>ESLOVENIA</option>
<option value='ES'>ESPA¥A</option>
<option value='FM'>MICRONESIA</option>
<option value='US'>E.U.A.</option>
<option value='EN'>ESTONIA</option>
<option value='ET'>ETIOPIA</option>
<option value='FJ'>FIJI</option>
<option value='PH'>FILIPINAS</option>
<option value='FI'>FINLANDIA</option>
<option value='FR'>FRANCIA</option>
<option value='GZ'>GAZA</option>
<option value='GM'>GAMBIA</option>
<option value='GE'>GEORGIA</option>
<option value='GA'>GHANA</option>
<option value='GD'>GRANADA</option>
<option value='GR'>GRECIA</option>
<option value='GL'>GROENLANDIA</option>
<option value='GP'>GUADALUPE</option>
<option value='GU'>GUAM</option>
<option value='GT'>GUATEMALA</option>
<option value='GN'>GUINEA</option>
<option value='GO'>GUINEA E.</option>
<option value='GW'>GUINEA B.</option>
<option value='GY'>GUYANA</option>
<option value='GF'>GUYANA FRANCESA</option>
<option value='HT'>HAITI</option>
<option value='HN'>HONDURAS</option>
<option value='HK'>HONG KONG</option>
<option value='HU'>HUNGRIA</option>
<option value='IN'>INDIA</option>
<option value='ID'>INDONESIA</option>
<option value='IQ'>IRAK</option>
<option value='IR'>IRAN</option>
<option value='IE'>IRLANDA</option>
<option value='IS'>ISLANDIA</option>
<option value='HM'>HEARD Y MCDONALD</option>
<option value='FK'>MALVINAS</option>
<option value='13'>MARIANAS</option>
<option value='MH'>MARSHALL</option>
<option value='SB'>SALOMON</option>
<option value='SJ'>SVALBARD Y J. M.</option>
<option value='TK'>TOKELAU</option>
<option value='WF'>WALLIS Y FUTUNA</option>
<option value='IL'>ISRAEL</option>
<option value='IT'>ITALIA</option>
<option value='JM'>JAMAICA</option>
<option value='JP'>JAPON</option>
<option value='JO'>JORDANIA</option>
<option value='KZ'>KAZAKHSTAN</option>
<option value='KE'>KENYA</option>
<option value='KI'>KIRIBATI</option>
<option value='KW'>KUWAIT</option>
<option value='KG'>KYRGYZSTAN</option>
<option value='LS'>LESOTHO</option>
<option value='LV'>LETONIA</option>
<option value='LB'>LIBANO</option>
<option value='LR'>LIBERIA</option>
<option value='LY'>LIBIA</option>
<option value='LI'>LIECHTENSTEIN</option>
<option value='LT'>LITUANIA</option>
<option value='LX'>LUXEMBURGO</option>
<option value='MO'>MACAO</option>
<option value='MK'>MACEDONIA</option>
<option value='MG'>MADAGASCAR</option>
<option value='MY'>MALASIA</option>
<option value='MW'>MALAWI</option>
<option value='MV'>MALDIVAS</option>
<option value='ML'>MALI</option>
<option value='MT'>MALTA</option>
<option value='MA'>MARRUECOS</option>
<option value='MQ'>MARTINICA</option>
<option value='MU'>MAURICIO</option>
<option value='MR'>MAURITANIA</option>
<option value='MX'>MEXICO</option>
<option value='MD'>MOLDAVIA</option>
<option value='MC'>MONACO</option>
<option value='MN'>MONGOLIA</option>
<option value='MS'>MONSERRAT</option>
<option value='MZ'>MOZAMBIQUE</option>
<option value='MM'>MYANMAR</option>
<option value='NA'>NAMIBIA</option>
<option value='NR'>NAURU</option>
<option value='CX'>NAVIDAD</option>
<option value='NL'></option>
<option value='NP'>NEPAL</option>
<option value='NI'>NICARAGUA</option>
<option value='NE'>NIGER</option>
<option value='NG'>NIGERIA</option>
<option value='NU'>NIVE</option>
<option value='NF'>NORFOLK</option>
<option value='NO'>NORUEGA</option>
<option value='NC'>N. CALEDONIA</option>
<option value='NZ'>N. ZELANDA</option>
<option value='OM'>OMAN</option>
<option value='14'>I. DEL PACIFICO</option>
<option value='6'>P. BAJOS</option>
<option value='7'>PAISES N.D.</option>
<option value='PK'>PAKISTAN</option>
<option value='PW'>PALAU</option>
<option value='PA'>PANAMA</option>
<option value='PG'>PAPUA N. GUINEA</option>
<option value='PY'>PARAGUAY</option>
<option value='PE'>PERU</option>
<option value='PN'>PITCAIRNS</option>
<option value='PF'>P. FRANCESA</option>
<option value='PL'>POLONIA</option>
<option value='PT'>PORTUGAL</option>
<option value='PR'>PUERTO RICO</option>
<option value='QA'>QATAR</option>
<option value='GB'>REINO U.</option>
<option value='CF'>R. CENTROAFRICANA</option>
<option value='CZ'>REPUBLICA CHECA</option>
<option value='LA'>R. LAOS</option>
<option value='DO'>R DOMINICANA</option>
<option value='20'>R DEL CONGO</option>
<option value='15'>R. RUANDESA</option>
<option value='17'>R SLOVAKIA</option>
<option value='RE'>REUNION</option>
<option value='RO'>RUMANIA</option>
<option value='RU'>RUSIA</option>
<option value='10'>SAHARA O.</option>
<option value='16'>SAMOA</option>
<option value='KN'>SAN CRISTOBAL.</option>
<option value='SM'>SAN MARINO</option>
<option value='PM'>SAN PEDRO</option>
<option value='VC'>SAN VICENTE</option>
<option value='SH'>SANTA ELENA</option>
<option value='LC'>SANTA LUCIA</option>
<option value='ST'>SANTO TOME</option>
<option value='SN'>SENEGAL</option>
<option value='SC'>SEYCHELLES</option>
<option value='SL'>S. LEONA</option>
<option value='00'>SIN PAIS</option>
<option value='SG'>SINGAPUR</option>
<option value='SY'>SIRIA</option>
<option value='SO'>SOMALIA</option>
<option value='LK'>SRI LANKA</option>
<option value='ZA'>SUDAFRICA</option>
<option value='SD'>SUDAN</option>
<option value='SE'>SUECIA</option>
<option value='CH'>SUIZA</option>
<option value='SR'>SURINAME</option>
<option value='SZ'>SWAZILANDIA</option>
<option value='TJ'>TADJIKISTAN</option>
<option value='TH'>TAILANDIA</option>
<option value='TW'>TAIWAN</option>
<option value='TZ'>TANZANIA</option>
<option value='IO'>T. BRITANICOS O. INDICO</option>
<option value='18'>T. FRANCESES AUSTRALES</option>
<option value='19'>TIMOR ORIENTAL</option>
<option value='TG'>TOGO</option>
<option value='TO'>TONGA</option>
<option value='TT'>TRINIDAD Y TOBAGO</option>
<option value='TN'>TUNEZ</option>
<option value='TC'>TURCAS Y CAICOS</option>
<option value='TM'>TURKMENISTAN</option>
<option value='TR'>TURQUIA</option>
<option value='TV'>TUVALU</option>
<option value='UA'>UCRANIA</option>
<option value='UG'>UGANDA</option>
<option value='UY'>URUGUAY</option>
<option value='UZ'>UZBEJISTAN</option>
<option value='VU'>VANUATU</option>
<option value='VE'>VENEZUELA</option>
<option value='VN'>VIETNAM</option>
<option value='VG'>I.VIRGENES BRIT</option>
<option value='VI'>I.VIRGENES AMER</option>
<option value='YD'>YEMEN</option>
<option value='YU'>YUGOSLAVIA</option>
<option value='ZM'>ZAMBIA</option>
<option value='ZW'>ZIMBABWE</option>
<option value='21'>CANAL DE PANAMA</option>
<option value='NT'>IRAQ-ARABIA</option>
</datalist>



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
    $("#txtModal").val("");
    $("#supplier_carrier_modLabel").val("");
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
    //$("#IncomeImgForm").submit();
    $("#sbtIncomeImgForm").click();
}
function guardarEntrada()
{
    //validaciones
    if($("#txtFecha").val().length < 1)
    {
        showModal("Alerta!", "Llene el campo fecha.");
        return;
    }
    // if($("#txtImpoExpo").val() == 0)
    // {
    //     showModal("Alerta!", "Llene el campo Impo/Expo.");
    //     return;
    // }
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
    $("#btnRegistrar").attr("disabled", true);
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
                $("#btnRegistrar").attr("disabled", false);
            } else
            {
                showModal("Notificación","Error: "+response+".");
                $("#btnRegistrar").attr("disabled", false);
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
    //este if se movera para adentro de la funcion ajax de mas abajo para permitir que aunque la partira ya este guardada podamos obtener informacion sobre si el numero de parte ya ha sido actualizado
    // if($('#txtNumeroDeParte').prop('readonly'))
    // {
    //     return;
    // }
    
    let numeroDeParte = $("#txtNumeroDeParte").val();
    let cliente = $("#txtCliente").val();

    if(numeroDeParte.length < 2)
    {
        return;
    }


    // $.ajax({url: "/part_number/"+numeroDeParte+"/"+cliente+"/get",context: document.body}).done(function(result) 
    //     {
    //         if(result.part_number == null)
    //         {
    //             if(confirm("El número de parte no existe, desea crearlo?"))
    //             {
    //                 //window.open('/part_number/' + numeroDeParte + '/' + cliente + '/' + NumEntrada + '/edit', '_blank').focus();
    //                 location.replace('/part_number/' + numeroDeParte + '/' + cliente + '/' + NumEntrada + '/edit');
    //             }
    //             else
    //             {
    //                 $('#txtNumeroDeParte').val("");
    //                 $('#txtNumeroDeParte').focus();
    //             }
    //             return;
    //         }
    //         fillPartidaFields(result);
    //         $("#incomeRowID").val("");
    //     });

    let urlpn = '/part_number/info/' + cliente + '/get';
    let token = $("[name='_token']").val();
    let encodedpn = '_token='+token+'&partNumber=' + numeroDeParte;

    //alert(encodedpn);

    $.ajax({
        method: 'POST',
        url: urlpn,
        data: encodedpn, 
        success: function(result) {
                //este es el if que se comento en los comentarios de mas arriba
                if($('#txtNumeroDeParte').prop('readonly'))
                {
                    if(result.part_number == null)
                    {
                        //este nunca deberia pasar pero porsi acaso
                        return;
                    }
                    if(result.created_at != result.updated_at)
                    {
                        $("#lblActualizado").html("Si <i class='fas fa-check-circle'></i>");
                    }
                    else
                    {
                        $("#lblActualizado").html("No <i class='far fa-times-circle'></i>");
                    }

                    return;
                }

                if(result.part_number == null)
                {
                    if(confirm("El número de parte no existe, desea crearlo?"))
                    {
                        //window.open('/part_number/' + numeroDeParte + '/' + cliente + '/' + NumEntrada + '/edit', '_blank').focus();
                        //location.replace('/part_number/' + numeroDeParte + '/' + cliente + '/' + NumEntrada + '/edit');
                        
                        $("#txtNP_post").val(numeroDeParte);
                        $("#frmEditNp").attr("action",'/part_number/info/' + cliente + '/' + NumEntrada + '/edit');
                        $("#frmEditNp").submit();
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
            },
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
    if(data.created_at != data.updated_at)
    {
        $("#lblActualizado").html("Si <i class='fas fa-check-circle'></i>");
    }
    else
    {
        $("#lblActualizado").html("No <i class='far fa-times-circle'></i>");
    }
    
    

    if(data.fraccion_especial != "")
    {
        $("#fraccionAlert").show();
        $("#fraccionAlert").html(data.fraccion_especial);
    }
    else
    {
        $("#fraccionAlert").removeAttr("style").hide();
    }

    //revisar si tiene alerta de proveedor (supplier warning)
    let txtProveedor = $("#txtProveedor").val();
    if(data.warning == txtProveedor)
    {
        showModal("Alterta","No se puede recibir este numero de parte con el proveedor seleccionado");
    }

    $('#txtNumeroDeParte').prop('readonly', false);

    if($("#txtNumeroDeParte").val() == "REVISION PENDIENTE")
    {
        $("#txtCantidad").val("1");
        $("#txtUM").val("Pieza");
        $("#txtUMB").val("Atados");
        $("#txtUMBPeso").val("1");
        $("#txtPesoNeto").val("1");
        $("#txtPesoBruto").val("1");
        $("#txtBultos").val("1");
        $("#txtBultos").focus();
        
    }
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
    $("#txtObservacionesPartida").val("");
    $("#fraccionAlert").removeAttr("style").hide();
    $("#fraccionAlert").html("");
    $('#txtNumeroDeParte').prop('readonly', false);
    $("#lblActualizado").html("");
}

function actualizarNP()
{
    let npid = $("#txtNumeroDeParteID").val();
    let NumEntrada = $("#txtNumEntrada").val();
    if(NumEntrada.length != 9)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }
    if(isNaN(npid))
    {
        return;
    }
    if(npid == "" || npid == "0")
    {
        return;
    }
    location.href = "/part_number/"+npid+"/"+NumEntrada+"/edit_existing";
    
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
            $("#txtNumeroDeParte").focus();

        });

}

function guardarPartida()
{
    if($("#txtNumEntrada").val().length != 9 || $("#incomeID").val().length < 1)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }

    actualizarSumario();

    if($("#txtNumeroDeParteID").val().length < 1)
    {
        showModal("Alerta!","Número de parte no valido.");
        return;
    }

    if($("#txtCantidad").val() <= 0)
    {
        showModal("Alerta!","'Cantidad' no puede ser 0.");
        return;
    }
    // if($("#txtBultos").val() <= 0)
    // {
    //     showModal("Alerta!","'Bultos' no puede ser 0.");
    //     return;
    // }
    if($("#txtUMB").val() <= 0)
    {
        showModal("Alerta!","'Tipo de Bultos' inválido.");
        return;
    }
    if($("#txtPesoNeto").val() <= 0)
    {
        showModal("Alerta!","'Peso neto' inválido.");
        return;
    }
    if($("#txtPesoBruto").val() <= 0)
    {
        showModal("Alerta!","'Peso bruto' inválido.");
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
            
            
            
            $.ajax({url: "/income_row_del/" + id_income_row,context: document.body}).done(function(response) 
            {
                showModal("Notificación","Partida Eliminada");
                actualizarSumario();
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
function terminar()
{
    let NumEntrada = $("#txtNumEntrada").val();
    let income_id = $("#incomeID").val();
    if(NumEntrada.length != 9 || income_id.length < 1)
    {
        showModal("Alerta!","Primero guarde la entrada.");
        return;
    }

    let enviada = "";
    @if (isset($income))
    @if ($income->sent)
    enviada = "Esta Entrada ya ha sido enviada ";
    @endif
    @endif

    if(!confirm(enviada + "¿Desea enviar la entrada por correo?"))
    {
        return;
    }

    $("#btnTerminar").prop("disabled",true);
    location.href = "/sendemail/"+NumEntrada+"/entrada";
    /*
    $.ajax({url: "/sendemail/"+NumEntrada+"/entrada",context: document.body}).done(function(response) 
        {
            showModal("Notificación", "Enviada: " + NumEntrada);
            $("#btnTerminar").prop("disabled",false);
        });
    */
}

function nuevaEntrada()
{
    if(!confirm("Desea crear una nueva Entrada? los datos no guardados serán descartados."))
    {
        return;
    }
    location.href='/int/entradas/create';
}

function actualizarSumario()
{
    let income_id = $("#incomeID").val();
    if(income_id.length < 1)
    {
        return;
    }
    $.ajax({url: "/int/entradas_get_sums/" + income_id,context: document.body}).done(function(response) 
        {
            $("#tdPesoNeto").html(response["peso_neto"]);
            $("#tdPesoBruto").html(response["peso_bruto"]);
            $("#tdPiezas").html(response["piezas"]);
            $("#tdBultos").html(response["bultos"]);
        });

    
}

function GuardarRevisionPendiente()
{
    guardarPartida();
    location.href = "/int/preentrada_etiqueta/" + $("#incomeID").val();
}

$(document).ready(function(){
    actualizarSumario();
});

</script>
@endsection