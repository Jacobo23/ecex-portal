<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
<style type="text/css">
    @import url('http://fonts.cdnfonts.com/css/libre-barcode-39-text');
    @page 
    { 
        margin: 0px; 
    }
    .page_break { page-break-before: always; }
    .barcode
    {
        font-family: "Libre Barcode 39";
    }
    body
    {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0px;
        text-align: center; 
    }
    .bold
    {	
        font-weight: bold;
    }

    .numEntrada
    {
        width:340px;
        height:70px;
        font-size: 75px;
        display: block;
        position:relative;
        top: -5px;
        left: 20px;
    }
    .bar
    {
        font-size: 50px;
        position:relative;
        top: -30px;
    }
    .bultos
    {
        font-size: 30px;
        position:relative;
        float: left;
        margin-left: 30px;
    }
    
    .fecha
    {
        font-size: 20px;
        position:relative;
        float: right;
        margin-right: 30px;
    }
    .revPendiente
    {
        font-size: 50px;
    }


</style>
</head>
<body>
    
    @php
    $bultos = $entrada->getBultosOriginales();
    $numEntrada = $entrada->getIncomeNumber();
    $fecha = strtotime($entrada->cdate);
    $fecha = date("M d, Y",$fecha);
    @endphp


    <!-- si se trata de una Revision Pendiente vamos a imprimir una etiqueta extra al principio con la leyenda "REVISION PENDIENTE" -->
    @if (!$entrada->reviewed)
    <br>
    <br>
    <label class="revPendiente">REVISION PENDIENTE</label>
    <div class="page_break"></div>
    @endif

    @for ($i = 1; $i <= $bultos; $i++)
    <label class="bold numEntrada">{{ $numEntrada }}</label>
    <br>
    <label class="bultos bold">{{ $i }}/{{ $bultos }}</label>
    <label class="fecha">{{ $fecha }}</label>

    <label class="bar barcode">*{{ $numEntrada }}{{ $i }}*</label>
    @if ($i < $bultos)
    <div class="page_break"></div>
    @endif
    @endfor
</body>
</html>