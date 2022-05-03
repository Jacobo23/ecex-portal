<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
<style type="text/css">
@import url('http://fonts.cdnfonts.com/css/libre-barcode-39-text');
    .barcode
    {
        font-family: "Libre Barcode 39";
    }
    body
    {
        font-family: Arial, Helvetica, sans-serif;
    }
    .bold
    {	
        font-weight: bold;
    }
    .page_break { page-break-before: always; }
    footer {
                /* position: fixed;  */
                bottom: -40px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                text-align: center;
                line-height: 35px;
            }
</style>
</head>
<body>
<div style="margin-top: 10px;">
    <table style="position:relative; top:-10px; " >
    <tbody>
    <tr>
        <td width="50">
            <img class="box" src="{{ public_path('storage/images/logo.png') }}" style="left: 0px; top: -30px; width:120px;">
        </td>
		<td width="500" >
            <table width="500" class="arial" style=" font-size: 15px; " >
                <tbody>
                    <tr>
                        <td nowrap="nowrap" width="50%" style="text-align: left; " ><a  class="bold">Cliente: </a>{{ $outcome->customer->name }}</td>
                        <td nowrap="nowrap" width="50%" style="text-align: left; "><a class="bold" style="text-align: left;">Transportista: </a>{{ $outcome->carrier->name }}</td>
                        
                    </tr>
                    <tr>
                        <td nowrap="nowrap" width="50%" style="text-align: left; " ><a class="bold" style="text-align: left;  ">Placas: </a>{{ $outcome->plate }}</td>
                        <td nowrap="nowrap" width="50%"><a class="bold" style="text-align: left; ">Referencia:</a> {{ $outcome->reference }}</td>
                    </tr>
                </tbody>
            </table>
            <table width="500" class="arial" style="margin-top: 20px; font-size: 13px; text-align: left;" >
                <tbody>
                    <tr>
                        <td width="40%" style="text-align: left"><a class="bold" style="">Factura:</a> {{ $outcome->invoice }}</td>
                        <td width="40%"><a class="bold" style="">Sello:</a> {{ $outcome->seal }}</td>
                        <td width="20%"><a class="bold">Caja:</a> {{ $outcome->trailer }}</td>
                    </tr>
                    <tr>
                        <td with="40%"><a class="bold"></td>
                        <td width="40%"><a class="bold">Pedimento:</a> {{ $outcome->pediment }}</td>
                        <td width="20%"></td>
                    </tr>
                </tbody>
            </table>
		</td>
        <td width="170">
            <table style="margin 0 auto; text-align: center;">
                <tbody >
                    <tr>
                        <td class="time">
                            Salida: <strong>{{ $outcome->getOutcomeNumber(true) }}</strong> 
                        </td>
                    </tr>
                    <tr>
                        <td class="barcode" style="font-size:40px;">
                            *{{ $outcome->getOutcomeNumber(false) }}*
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Fecha: {{ date("m/d/Y",strtotime($outcome->cdate)) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                        </td>
                    </tr>

                    
                </tbody>

            </table>
	    </td>
		
    </tr>
    </tbody>
    </table>

<hr width="100%">


</div>
<!--de aqui hasta el siguiente comentario!-->
@php
$break = 4;
$pages = 1;
$it = 0;
foreach ($outcome->outcome_rows as $outcome_row) 
{
  $it++;
  if ($it % $break == 0)
  {
    $pages++;
    $it = 0;
  }
}

$page = 1;
@endphp
@foreach($outcome->outcome_rows as $outcome_row)
<div>
<table width="1000px" style="font-size: 11px; " class="arial"  >
  <tbody>
    <tr  style="    background: #eee;">
      <th height="" width="10%" scope="col">{{ $loop->iteration }} No. Parte</th>
      <th scope="col" width="10%">Cantidad</th>
      <th scope="col" width="10%">Bultos</th>
      <th scope="col" width="20%">Peso Nt: Lbs/Kg</th>
      <th scope="col" width="20%">Peso Bt: Lbs/kg</th>
      <th scope="col" width="10%">País</th>
      <th scope="col" width="10%">Fracción-Nico</th>
      <th scope="col" width="10%">Entrada</th>
      <th scope="col" width="10%">Clasificación</th>
    </tr>
    <tr style="">
      <td style="text-align:center">{{ $outcome_row->income_row->part_number()->part_number }}</td>
      <td style="text-align:center">{{ $outcome_row->units * 1 }} {{ $outcome_row->ump }}</td>
      <td style="text-align:center">{{ $outcome_row->bundles }} {{ $outcome_row->umb }}</td>
      <td style="text-align:center">{{ $outcome_row->net_weight * 1 }} Lbs / {{ round($outcome_row->net_weight *  0.453592,2,PHP_ROUND_HALF_UP )}} Kg</td>
      <td style="text-align:center">{{ $outcome_row->gross_weight * 1 }} Lbs / {{ round($outcome_row->gross_weight *  0.453592,2,PHP_ROUND_HALF_UP )}} Kg</td>
      <td style="text-align:center">{{ $outcome_row->income_row->origin_country }}</td>
      <td style="text-align:center">{{ $outcome_row->income_row->fraccion }}-{{ $outcome_row->income_row->nico }}</td>
      <td style="text-align:center">{{ $outcome_row->income_row->income->getIncomeNumber() }}</td>
      <td style="text-align:center">{{ $outcome_row->income_row->income->type }}</td>
    </tr>
  </tbody>
</table>
<table class="arial" width="1000px" style=" font-size: 11px"  >
  <tbody>
    <tr style="background: #eee; ">
      <th height="" scope="col" width="30%">Desc Ing</th>
      <th scope="" width="30%%">Desc Esp</th>

      <th scope="" width="40%">Obeservaciones</th>
    </tr>
    <tr >
      <td>{{ $outcome_row->income_row->desc_ing }}</td>
      <td>{{ $outcome_row->income_row->desc_esp }}</td>
      <td>{{ $outcome_row->income_row->observations }}</td>
    </tr>
  </tbody>
</table>
@if ( $outcome_row->income_row->brand != null || $outcome_row->income_row->model != null || $outcome_row->income_row->serial != null || $outcome_row->income_row->imex != null)
<table class="arial" width="1000px" style=" font-size: 11px"  >
  <tbody>
    <tr style="background: #eee; ">
        <th height="" scope="col" width="20%">Marca</th>
        <th scope="" width="20%%">Modelo</th>
        <th scope="" width="20%">Serie</th>
        <th scope="" width="20%">Imex</th>
    </tr>
    <tr>
        <td>{{ $outcome_row->income_row->brand }}</td>
        <td>{{ $outcome_row->income_row->model }}</td>
        <td>{{ $outcome_row->income_row->serial }}</td>
	      <td>{{ $outcome_row->income_row->imex }}</td>
    </tr>
  </tbody>
</table>
@endif
</div>
<hr  width="1000" style="border-top: 1px solid	" size="3">

@if ($loop->iteration % $break == 0)
<footer style="text-align:center; font-size: small">
    Recibido por: {{ $outcome->received_by }} - page: {{ $page }} of {{ $pages }}
</footer>
<div class="page_break"></div>
@php
$page++;
@endphp
@endif

@endforeach
<!-- y aqui se  duplica en caso de mas de 1 partida-->

  <table width="1000px" style="" >							
  <tbody>
    <tr>
      <td><b class="arial bold" style="font-size: small; ">Observaciones:</b> <p class="arial" style="font-size: small"> {{ $outcome->observations }}</p></td>
    </tr>
  </tbody>
</table>
<table class="arial" width="1000px" style="font-size: small">
    <tr>
        <td><strong> Peso Neto </strong></td>
        <td><strong> Peso Bruto </strong></td>
        <td><strong> Piezas UM </strong></td>
        <td><strong> Bultos UM </strong></td>
    </tr>
    <tr>
        <td id="tdPesoNeto">{{ $outcome->getPesoNeto() }} lbs / {{ round( $outcome->getPesoNeto() * 0.453592,2,PHP_ROUND_HALF_EVEN) }} kg</td>
        <td id="tdPesoBruto">{{ $outcome->getPesoBruto() }} lbs / {{ round( $outcome->getPesoBruto() * 0.453592,2, PHP_ROUND_HALF_EVEN) }} kg</td>
        <td id="tdPiezas">{!! str_replace("<br>","<br>",$outcome->getPiezasSum()) !!}</td>
        <td id="tdBultos">{!! str_replace("<br>","<br>",$outcome->getBultosSum()) !!}</td>
    </tr>
    <tr>
        <td > </td>
        <td > </td>
        <td id="tdPiezas">Total: {!! str_replace("<br>","<br>",$outcome->getPiezasTotalSum()) !!}</td>
        <td id="tdBultos">Total: {!! str_replace("<br>","<br>",$outcome->getBultosTotalSum()) !!}</td>
    </tr>
</table>
<br>
	 <footer style="text-align:center; font-size: small">
   Recibido por: {{ $outcome->received_by }} - page: {{ $page }} of {{ $pages }}
    </footer>
</body>
</html>