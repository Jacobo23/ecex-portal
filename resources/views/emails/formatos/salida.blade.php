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
            Enviar correo de salida: {{ $outcome->getOutcomeNumber(true) }} - {{ $outcome->customer->name }}
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <form id="formEmail" action="/sendemail_done/salida" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <input type="hidden" name="outcomeID" id="outcomeID" value="{{ $outcome->id }}">
            <div class="col-lg-10 controlDiv" style="">
                <label class="form-label">Para:</label>
                <input type="text" class="form-control" id="txtTo" name="txtTo" value="{{ $outcome->customer->emails_add }}" onfocusout="formatEmails()">       
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 controlDiv" style="">
                <label class="form-label">Cc:</label>
                <input type="text" class="form-control" id="txtCc" name="txtCc" onfocusout="formatEmails()">       
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-lg-2 controlDiv form-check form-switch" >
                <input class="form-check-input" type="checkbox" id="chkPDF" name="chkPDF" checked>
                <label class="form-check-label" for="chkPDF">PDF {{ $outcome->getOutcomeNumber(false) }}</label>
            </div>

            <div class="col-lg-2 controlDiv form-check form-switch">
                <input class="form-check-input" type="checkbox" id="chkPck" name="chkPck" checked>
                <label class="form-check-label" for="chkPck">Packing list</label>
            </div>

            <div class="col-lg-2 controlDiv form-check form-switch" >
                <input class="form-check-input" type="checkbox" id="chkImgs" name="chkImgs">
                <label class="form-check-label" for="chkImgs">Enviar imagenes</label>
            </div>

            <div class="col-lg-2 controlDiv form-check form-switch" >
                <input class="form-check-input" type="checkbox" id="chkIncome" name="chkIncome">
                <label class="form-check-label" for="chkIncome">Adjuntos de entradas</label>
            </div>

            <div class="col-lg-2 controlDiv" >
                <input type="file" name="filenames[]" id="txtAdjuntos" multiple>
            </div>

        </div>
        <br>

        <div class="row">
            <div class="col-lg-10 controlDiv" style="">
                <label class="form-label">Asunto:</label>
                <input type="text" class="form-control" id="txtSubject" name="txtSubject" value="Salida: {{ $outcome->getOutcomeNumber(true) }}">       
            </div>
        </div>

        <h5 class="separtor">Cuerpo:</h5>

        <div style="width:80%; margin: 0 auto">
            <h1 style="background-color:#b80202; text-align:center; color: white;">Salida: {{ $outcome->getOutcomeNumber(true) }}</h1>
            <table style="margin:0 auto; min-width:600px;">
                <tr>
                    <td style="text-align:center"><strong>Cliente</strong></td>
                    <td style="text-align:center"><strong>Referencia</strong></td>
                    <td style="text-align:center"><strong>Bultos</strong></td>
                    <td style="text-align:center"><strong>Tipo de bulto</strong></td>
                    <td style="text-align:center"><strong>Peso Neto</strong></td>
                    <td style="text-align:center"><strong>Peso Bruto</strong></td>
                </tr>
                <tr>
                    <td style="text-align:center">{{ $outcome->customer->name }}</td>
                    <td style="text-align:center">{{ $outcome->reference }}</td>
                    <td style="text-align:center">{{ $outcome->getBultos() }}</td>
                    <td style="text-align:center">{{ $outcome->getTipoBultos() }}</td>
                    <td style="text-align:center">{{ $outcome->getPesoNeto() }}</td>
                    <td style="text-align:center">{{ $outcome->getPesoBruto() }}</td>
                </tr>
            </table>
        </div>

        <div class="mb-3">
            <label class="form-label">Observaciones</label>
            <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="2">{{ $outcome->observations ?? '' }}</textarea>
        </div>

        <div class="col-lg-3 controlDiv">
            <button type="button" class="btn btn-success" onclick="sent()">Enviar <i class="far fa-envelope"></i></button>     
        </div>
            
        </form>

</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>

function sent()
{
    if(!confirm("Enviar correo?"))
    {
        return;
    }
    $("#formEmail").submit();
}

function formatEmails()
{
    //
    let to = $("#txtTo").val().replace(" ", "");
    to = to.replace(",", ";");
    to = to.replace(";;", ";");
    to = to.replace(";", "; ");
    $("#txtTo").val(to);

    let cc = $("#txtCc").val().replace(" ", "");
    cc = cc.replace(",", ";");
    cc = cc.replace(";;", ";");
    cc = cc.replace(";", "; ");
    $("#txtCc").val(cc);
}

</script>
@endsection