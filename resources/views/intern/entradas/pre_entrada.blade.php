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
            Pre-Entrada
        </h2>
    </div>
</header>

<!-- Page Content -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
            
            <form action="/int/preentrada/imprimir" method="post" enctype="multipart/form-data">
                @csrf

            <div class="row">

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Fecha:</label>
                    <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="{{date('Y-m-d')}}" required>
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Cliente:</label>
                    <select class="form-select" id = "txtCliente" name = "txtCliente" required>
                    <option value=""></option>
                    @foreach ($clientes as $clienteOp)
                    <option value="{{ $clienteOp->id }}" @php if(isset($income)){if($income->customer_id == $clienteOp->id){echo "selected";}}@endphp >{{ $clienteOp->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Bultos:</label>
                    <input type="number" class="form-control" id="txtBultos" name="txtBultos" required>       
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Locación:</label>
                    <input type="text" class="form-control" id="txtLocacion" name="txtLocacion" value="">       
                </div>

            </div>

            <div class="row">
                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Transportista:</label>
                    <select class="form-select" id = "txtTransportista" name = "txtTransportista" onchange="agregarTransportista()" required>
                    <option value=""></option>
                    @foreach ($transportistas as $transportistaOp)
                    <option value="{{ $transportistaOp->id }}" @php if(isset($income)){if($income->carrier_id == $transportistaOp->id){echo "selected";}}@endphp >{{ $transportistaOp->name }}</option>
                    @endforeach
                    <option value = "-2" id="option_new_transportista" >(Crear nuevo +)</option>
                    </select>
                </div>
                <div class="col-lg-3" >
                    <label class="form-label">Proveedor:</label>
                    <select class="form-select" id = "txtProveedor" name = "txtProveedor" onchange="agregarProveedor()" required>
                    <option value=""></option>
                    @foreach ($proveedores as $proveedoresOp)
                    <option value="{{ $proveedoresOp->id }}" @php if(isset($income)){if($income->supplier_id == $proveedoresOp->id){echo "selected";}}@endphp >{{ $proveedoresOp->name }}</option>
                    @endforeach
                    <option value = "-2" id="option_new_proveedor" >(Crear nuevo +)</option>
                    </select>
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Tracking:</label>
                    <input type="text" class="form-control" id="txtTracking" name="txtTracking" value="">       
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">PO:</label>
                    <input type="text" class="form-control" id="txtPO" name="txtPO" value="">       
                </div>

                

            </div>

            <div class="row">

            <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="txtUsuario" name="txtUsuario" value="">       
                </div>

                

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Packinglist:</label>
                    <input type="file" class="form-control" id="txtArchivo" name="file" accept="application/pdf">       
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Imagenes:</label>
                    <input type="file" class="form-control" id="txtImagenes" name="imagenes[]" accept="image/*" multiple>       
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="2"></textarea>
                </div>

                <input type="submit" class="btn btn-success" value="Guardar & Imprimir" onclick="nueva()">
            </div>

            </form>


                
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

function nueva()
{
    if($("#txtFecha").val() == "" || $("#txtCliente").val() == "" || $("#txtBultos").val() == "" || $("#txtTransportista").val() == "" || $("#txtProveedor").val() == "")
    {
        return;
    }
    window.open("/int/preentrada/create", '_blank');
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


</script>
@endsection