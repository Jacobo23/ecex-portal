@extends('layouts.common')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Número de parte
        </h2>
    </div>
</header>

<!-- Page Content -->
<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

<form id="PartNumberForm" action="/part_number" method="post">
@csrf
<div class="row">
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Número de parte:</label>
        <input type="text" class="form-control" id="txtNumeroDeParte" name="txtNumeroDeParte" value="{{ $part_number ?? '' }}" style="text-align:center;" @if ($part_number != '') readonly @endif>       
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Cliente:</label>
        <select class="form-select" id = "txtCliente" name = "txtCliente" @if (isset($cliente)) disabled @endif>
        <option value=0 selected></option>
        @foreach ($clientes as $clienteOp)
        <option value="{{ $clienteOp->id }}" @php if(isset($cliente)){if($cliente == $clienteOp->id){echo "selected";}}@endphp >{{ $clienteOp->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">UM:</label>
        <select class="form-select" id = "txtUM" name = "txtUM">
        @foreach ($unidades_de_medida as $unidade_de_medidaOp)
        <option value="{{ $unidade_de_medidaOp->desc }}">{{ $unidade_de_medidaOp->desc }}</option>
        @endforeach
        </select>
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Peso unitario:</label>
        <input type="number" class="form-control" id="txtPesoUnitario" name="txtPesoUnitario" value="@if (isset($part_number_obj)){{ $part_number_obj->unit_weight }}@endif">       
    </div>
</div>
<div class="row">
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Descripción Inglés:</label>
        <input type="text" class="form-control" id="txtDescIng" name="txtDescIng" value="@if (isset($part_number_obj)){{ $part_number_obj->desc_ing }}@endif">       
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Descripción Español:</label>
        <input type="text" class="form-control" id="txtDescEsp" name="txtDescEsp" value="@if (isset($part_number_obj)){{ $part_number_obj->desc_esp }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">País:</label>
        <input type="text" class="form-control" id="txtPais" name="txtPais" value="@if (isset($part_number_obj)){{ $part_number_obj->origin_country }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">fracción:</label>
        <input type="text" class="form-control" id="txtFraccion" name="txtFraccion" value="@if (isset($part_number_obj)){{ $part_number_obj->fraccion }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">nico:</label>
        <input type="text" class="form-control" id="txtNico" name="txtNico" value="@if (isset($part_number_obj)){{ $part_number_obj->nico }}@endif">       
    </div>
</div>
<div class="row">
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">marca:</label>
        <input type="text" class="form-control" id="txtMarca" name="txtMarca" value="@if (isset($part_number_obj)){{ $part_number_obj->brand }}@endif">       
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">modelo:</label>
        <input type="text" class="form-control" id="txtModelo" name="txtModelo" value="@if (isset($part_number_obj)){{ $part_number_obj->model }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">serie:</label>
        <input type="text" class="form-control" id="txtSerie" name="txtSerie" value="@if (isset($part_number_obj)){{ $part_number_obj->serial }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">IMMEX:</label>
        <input type="text" class="form-control" id="txtIMMEX" name="txtIMMEX" value="@if (isset($part_number_obj)){{ $part_number_obj->imex }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">regimen:</label>
        <input type="text" class="form-control" id="txtRegimen" name="txtRegimen" value="@if (isset($part_number_obj)){{ $part_number_obj->regime }}@endif">       
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Observaciones de fracción</label>
    <textarea class="form-control" id="txtObservacionesFraccion" name="txtObservacionesFraccion" rows="2">@if (isset($part_number_obj)){{ $part_number_obj->fraccion_especial }}@endif</textarea>
</div>

<input type="hidden" name="from_Incomes" value="{{ $from_income ?? '' }}">

<div class="row" style="margin-top:20px;">
    <div class="col-lg-9 controlDiv"></div>
    <input type="button" class="col-lg-3 btn btn-success " value="Guardar" onclick="guardar()">
</div>
</form>

</div>
</div>
</div>
</div>

@endsection
@section('scripts')
<script>

function guardar()
{
    //validaciones
    if($("#txtNumeroDeParte").val().length < 1)
    {
        showModal("Alerta!", "Llene el campo número de parte.");
        return;
    }
    if($("#txtCliente").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Cliente.");
        return;
    }
    if($("#txtUM").val() == 0)
    {
        showModal("Alerta!", "Llene el campo UM.");
        return;
    }
    if($("#txtPesoUnitario").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Peso unitario.");
        return;
    }
    if($("#txtDescIng").val() == "")
    {
        showModal("Alerta!", "Llene el campo Descripción en inglés.");
        return;
    }
    if($("#txtDetxtDescEspscIng").val() == "")
    {
        showModal("Alerta!", "Llene el campo Descripción en español.");
        return;
    }
    if($("#txtPais").val() == "")
    {
        showModal("Alerta!", "Llene el campo País.");
        return;
    }
    if($("#txtFraccion").val().length != 8)
    {
        showModal("Alerta!", "Llene correctamente el campo Fracción.");
        return;
    }
    if($("#txtNico").val().length != 2)
    {
        showModal("Alerta!", "Llene correctamente el campo nico.");
        return;
    }
    document.getElementById("PartNumberForm").submit();
}
</script>
@endsection