<html>
<body>

<h1 style="background-color:#b80202; text-align:center; color: white;">Salida: {{ $outcome->getOutcomeNumber(true) }}</h1>
<div>
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

{{ $body }}
    
</body>
</html>