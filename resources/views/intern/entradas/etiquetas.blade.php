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
        font-size: 68px;
        display: block;
        position:relative;
        top: -15px;
        left: -2px;
    }
    .bar
    {
        font-size: 30px;
    }
    .bultos
    {
        font-size: 22px;
    }
    
    .fecha
    {
        font-size: 13px;
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
    @for ($i = 1; $i <= $bultos; $i++)
    <label class="bold numEntrada">{{ $numEntrada }}</label>
    <label><small class="bar barcode">*{{ $numEntrada }}{{ $i }}*</small> &nbsp;<small class="bultos bold">{{ $i }}/{{ $bultos }}</small>&nbsp;<small class="fecha">{{ $fecha }}</small></label>
    @if ($i < $bultos)
    <div class="page_break"></div>
    @endif
    @endfor
</body>
</html>