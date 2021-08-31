@extends('layouts.common')
@section('headers')
<style>
    td
    {
        text-align:center;
    }
    .oversized-col
    {
        max-width:150px;
        overflow:hidden;
    }
</style>
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar cliente.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <h5 class="separtor">Campos:</h5>

        <form id = "frm_customer" action="/int/catalog/customers" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-2 controlDiv">
                <label class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="txtNombre" name="txtNombre" value="@if (isset($customer)){{ $customer->name }}@endif" placeholder="cliente" @if (isset($customer)) readonly @endif>       
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Correos 1</label>
            <textarea class="form-control" id="txtCorreos1" name="txtCorreos1" rows="3">@if (isset($customer)){{ $customer->emails }}@endif</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Correos 2</label>
            <textarea class="form-control" id="txtCorreos2" name="txtCorreos2" rows="3">@if (isset($customer)){{ $customer->emails_add }}@endif</textarea>
        </div>
        <div class="row">
            <div class="col-lg-2 controlDiv" style="float: right">
                <button type="button" onclick="guardar()" class="btn btn-success">Guardar</button>     
            </div>
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
        if($("#txtNombre").val().trim() == "")
        {
            showModal("Validación", "Lléne el nombre del cliente.");
            return;
        }
        $("#frm_customer").submit();
        
    }

</script>
@endsection