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
    /* .barcode
    {
        font-family: "Arial";
    } */
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
                position: relative;  
                top: 5px; 
                left: 0px; 
                right: 0px;

                text-align: center;
            }
            @page {
            margin: 50px 20px 3px 20px !important;
            padding: 0px 0px 0px 0px !important;
        }
</style>
</head>
<body>
<div style="margin-top: 0px; padding: 0px">
    <table style="position:relative; top: -30px; " >
    <tbody>
    <tr>
        <td width="50">
            <img class="box" src="{{ public_path('storage/images/logo.png') }}" style="left: 0px; top: -30px; width:120px;">
        </td>
		<td width="500" >
            <table width="500" class="arial" style=" font-size: 15px; " >
                <tbody>
                    <tr>
                        <td nowrap="nowrap" width="50%" style="text-align: left; " ><a  class="bold">Cliente: </a>{{ $income->customer->name }}</td>
                        <td nowrap="nowrap" width="50%" style="text-align: left; " ><a class="bold" style="text-align: left;  ">Impo/Expo: </a>{{ $income->impoExpo }}</td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" width="50%" style="text-align: left; "><a class="bold" style="text-align: left;">Transportista: </a>{{ $income->carrier->name }}</td>
                        <td nowrap="nowrap" width="50%"><a class="bold" style="text-align: left; ">Proveedor: </a>{{ $income->supplier->name }} </td>
                    </tr>
                </tbody>
            </table>
            <table width="500" class="arial" style="margin-top: 20px; font-size: 13px; text-align: left;" >
                <tbody>
                    <tr>
                        <td width="40%" style="text-align: left"><a class="bold" style="">Factura:</a> {{ $income->invoice }}</td>
                        <td width="40%"><a class="bold" style="">Sello:</a> {{ $income->seal }}</td>
                        <td width="20%"><a class="bold">Caja:</a> {{ $income->trailer }}</td>
                    </tr>
                    <tr>
                        <td with="40%"><a class="bold">Referencia:</a> {{ $income->reference }}</td>
                        <td width="40%"><a class="bold">Tracking:</a> {{ $income->tracking }}</td>
                        <td width="20%"></td>
                    </tr>
                </tbody>
            </table>
		</td>
        <td width="170">
            <table style="margin 0 auto; text-align: center;">
                <tbody >
                    @if ($income->urgent)
                    <tr>
                        <td class="time">
                            <strong>* Urgente *</strong> 
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="time" style="font-size: 20px;">
                            Entrada: <strong>{{ $income->getIncomeNumber() }}</strong> 
                        </td>
                    </tr>
                    <tr>
                        <td class="barcode" style="font-size:37px;">
                            *{{ $income->getIncomeNumber() }}*
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Fecha: {{ date("m/d/Y",strtotime($income->cdate)) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ $income->type }}
                        </td>
                    </tr>

                    
                </tbody>

            </table>
	    </td>
		
    </tr>
    </tbody>
    </table>




</div>
<hr width="100%">
<!--de aqui hasta el siguiente comentario!-->
@php
$break = 4;
$pages = 1;
$it = 0;
foreach ($income->income_rows as $income_row) 
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
@foreach($income->income_rows as $income_row)
<div>
<table width="100%" style="font-size: 10px; " class="arial"  >
  <tbody>
    <tr  style="    background: #eee;">
      <th height="" width="10%" scope="col">{{ utf8_encode($loop->iteration) }} No. Parte</th>
      <th scope="col" width="10%">Cantidad</th>
      <th scope="col" width="10%">Bultos</th>
      <th scope="col" width="20%">Peso Nt: Lbs/Kg</th>
      <th scope="col" width="20%">Peso Bt: Lbs/kg</th>
      <th scope="col" width="10%">Pais</th>
      <th scope="col" width="10%">Fraccion-Nico</th>
      <th scope="col" width="10%">PO</th>
    </tr>
    <tr style="">
      <td style="text-align:center">{{ utf8_encode($income_row->part_number()->part_number) }}</td>
      <td style="text-align:center">{{ utf8_encode($income_row->units * 1) }}  {{ utf8_encode($income_row->ump) }}</td>
      <td style="text-align:center">{{ utf8_encode($income_row->getBultos()) }} {{ utf8_encode($income_row->umb) }}</td>
      <td style="text-align:center">{{ utf8_encode($income_row->net_weight * 1) }} Lbs / {{ utf8_encode(round($income_row->net_weight *  0.453592,2,PHP_ROUND_HALF_UP ))}} Kg</td>
      <td style="text-align:center">{{ utf8_encode($income_row->gross_weight * 1) }} Lbs / {{ utf8_encode(round($income_row->gross_weight *  0.453592,2,PHP_ROUND_HALF_UP ))}} Kg</td>
      <td style="text-align:center">{{ utf8_encode($income_row->origin_country) }}</td>
      <td style="text-align:center">{{ utf8_encode($income_row->fraccion) }}-{{ utf8_encode($income_row->nico) }}</td>
      <td style="text-align:center">{{ utf8_encode($income_row->po) }}</td>
    </tr>
  </tbody>
</table>
<table class="arial" width="100%" style=" font-size: 11px"  >
  <tbody>
    <tr style="background: #eee; ">
      <th height="" scope="col" width="30%">Desc Ing</th>
      <th scope="" width="30%%">Desc Esp</th>

      <th scope="" width="40%">Obeservaciones</th>
    </tr>
    <tr >
      <td>{{ utf8_encode($income_row->desc_ing) }}</td>
      <td>{{ utf8_encode($income_row->desc_esp) }}</td>
      <td>{{ utf8_encode($income_row->observations) }}</td>
    </tr>
  </tbody>
</table>

<table class="arial" width="100%" style=" font-size: 11px"  >
  <tbody>
    <tr style="background: #eee; ">
        <th height="" scope="col" width="20%">Locacion</th>
        <th height="" scope="col" width="20%">Marca</th>
        <th scope="" width="20%%">Modelo</th>
        <th scope="" width="20%">Serie</th>
        <th scope="" width="20%">Imex</th>
    </tr>
    <tr>
        <td>{{ utf8_encode($income_row->location) }}</td>
        <td>{{ utf8_encode($income_row->brand) }}</td>
        <td>{{ utf8_encode($income_row->model) }}</td>
        <td>{{ utf8_encode($income_row->serial) }}</td>
	    <td>{{ utf8_encode($income_row->imex) }}</td>
    </tr>
  </tbody>
</table>
@if ($page != 1)
<br>
@endif
</div>
<hr  width="100%" style="border-top: 1px solid	" size="3">

@if ($loop->iteration % $break == 0)

<footer style="text-align:center; font-size: small">
    Usuario: {{ utf8_encode($income->user) }} - page: {{ utf8_encode($page) }} of {{ utf8_encode($pages) }}
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
      <td><b class="arial bold" style="font-size: small; ">Observaciones:</b> <p class="arial" style="font-size: small"> {{ $income->observations }}</p></td>
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
        <td id="tdPesoNeto">{{ $income->getPesoNeto() }} lbs / {{ round( $income->getPesoNeto() * 0.453592,2,PHP_ROUND_HALF_EVEN) }} kg</td>
        <td id="tdPesoBruto">{{ $income->getPesoBruto() }} lbs / {{ round( $income->getPesoBruto() * 0.453592,2, PHP_ROUND_HALF_EVEN) }} kg</td>
        <td id="tdPiezas">{!! str_replace("<br>","<br>",$income->getPiezasSum()) !!}</td>
        <td id="tdBultos">{!! str_replace("<br>","<br>",$income->getBultosSum()) !!}</td>
    </tr>
    <tr>
        <td id="tdPesoNeto"> </td>
        <td id="tdPesoBruto"> </td>
        <td id="tdPiezas"> <strong>Total:</strong> {!! str_replace("<br>","<br>",$income->getPiezasTotalSum()) !!}</td>
        <td id="tdPiezas"> <strong>Total:</strong> {!! str_replace("<br>","<br>",$income->getBultosTotalSum()) !!}</td>
    </tr>
</table>

<br>
<footer style="text-align:center; font-size: small">
    Usuario: {{ $income->user }} - page: {{ $page }} of {{ $pages }}
</footer>


</body>
</html>