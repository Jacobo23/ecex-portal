@extends('layouts.common_customer')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Orden de carga.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

    <input type="hidden" class="outPost" name="_token" value="{{ csrf_token() }}" />

    <div class="row">

        <div class="col-lg-3 controlDiv" >
            <label class="form-label">Regimen:</label>
            <select class="form-select outPost" id = "txtRegimen" name = "txtRegimen">
            <option value=0 selected></option>
            @foreach ($regimes as $regimeOp)
            <option value="{{ $regimeOp->name }}">{{ $regimeOp->name }}</option>
            @endforeach
            </select>
        </div>

    </div>


    <div class="mb-3">
        <label class="form-label">Observaciones</label>
        <textarea class="form-control outPost" id="txtObservaciones" name="txtObservaciones" rows="2"></textarea>
    </div>


    <div class="row" style="margin-top:20px;">
        <div class="col-lg-10 controlDiv"></div>
        <input type="button" class="col-lg-2 btn btn-success" onclick="guardarOC()" value="Registrar">
    </div>

    
    
    

    <h5 class="separtor">Partidas</h5>

    <div class="row">
        <div class="col-lg-4 controlDiv" >
            <label class="form-label">Rango:</label>
            <div class="input-group">
                <select class="form-select" id = "txtRangoInv" name = "txtRangoInv">
                    <option value=30 selected>30 días</option>
                    <option value=90>90 días</option>
                    <option value=190>6 meses</option>
                    <option value=365>1 año</option>
                </select>
                <button type="button" class="btn btn-outline-secondary" onclick="consultarInventario()">Consultar</button>
            </div>
        </div>
    </div>
    <br>
    <div id = "tbl_inv" class="row">

    </div>


</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>

function consultarInventario()
{
    let rango = $("#txtRangoInv").val();
    $("#tbl_inv").html("Cargando ");
    $.ajax({url: "/ext/inventory_oc/"+rango,context: document.body}).done(function(result) 
        {
            $("#tbl_inv").html(result);
        });
}

function selectRow(row_id)
{
    var check = !$("#btncheck_"+row_id).prop('checked'); 
    $("#txtCantidad_"+row_id).prop('readonly', check);
    if(!check)
    {
        $("#inv_row_"+row_id).addClass("table-warning");
    }
    else{
        $("#inv_row_"+row_id).removeClass("table-warning");
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

function guardarOC()
{
    let partidas = 0;
    let partidas_obj = $(".chkSingle");
    if(partidas_obj.length < 1)
    {
        showModal("Notificacion", "No hay partidas seleccionadas.");
        return;
    }
    let tbl = "<table class='table'><tr> <td></td><td><strong>Entrada</strong></td><td><strong>Numero de parte</strong></td><td><strong>Cantidad</strong></td></tr>";

    $(".chkSingle").each(function(){
        if ($(this).prop('checked'))
        {
            partidas++;
            let id_current = $(this).attr('id').split("_")[1];

            tbl += "<tr>";
            tbl += "<td>"+ partidas +"</td>";
            tbl += "<td>"+ $("#lblIncome_"+id_current).html() +"</td>";
            tbl += "<td>"+ $("#tdPN_"+id_current).html() +"</td>";
            tbl += "<td>"+ $("#txtCantidad_"+id_current).val() +"</td>";
            tbl += "</tr>";
            
        }
    });
    tbl += "</table>";
    if(partidas < 1)
    {
        showModal("Notificacion", "No hay partidas seleccionadas.");
        return;
    }

    showModal("Notificacion", tbl + " <br> <input type='button' class='col-lg-4 btn btn-primary' onclick='guardarOC2()' value='Confirmar'>");
    

}

function guardarOC2()
{
    $(".chkSingle").each(function(){
        if ($(this).prop('checked'))
        {
            var current_row_id = $(this).attr('id').split("_")[1]; 
            //outcomeID  <- ya contiene la clase
            $("#txtIncomeRowId_"+current_row_id).addClass("outPost");
            $("#txtCantidad_"+current_row_id).addClass("outPost");
        }
    });
    $.ajax({
        method: 'POST',
        url: "/ext/ordenes_de_carga",
        data: $(".outPost").serialize(), 
        success: function(response) {
            showModal("Notificación","Se registraron " + response.length + " partidas.");
            window.location.replace("/ext/ordenes_de_carga");
        },
    });

}

function validarCantidad(control,max)
{
    if(control.value > max)
    {
        showModal("Precaución!","Esta partida tiene un máximo de <strong>'" + max + "'</strong> unidades, verifíque la cantidad.");
        control.value = max;
    }
}

</script>
@endsection