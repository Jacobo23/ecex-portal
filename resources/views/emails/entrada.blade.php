<html>
<body>

<h1 style="background-color:#b80202; text-align:center; color: white;">Entrada: {{ $income->getIncomeNumber() }}</h1>
<div>
    <table style="margin:0 auto; min-width:600px;">
        <tr>
            <td style="text-align:center"><strong>Cliente</strong></td>
            <td style="text-align:center"><strong>PO</strong></td>
            <td style="text-align:center"><strong>Tracking</strong></td>
            <td style="text-align:center"><strong>Bultos</strong></td>
            <td style="text-align:center"><strong>Tipo de bulto</strong></td>
            <td style="text-align:center"><strong>Peso Neto</strong></td>
            <td style="text-align:center"><strong>Peso Bruto</strong></td>
        </tr>
        <tr>
            <td style="text-align:center">{{ $income->customer->name }}</td>
            <td style="text-align:center">{{ $income->po }}</td>
            <td style="text-align:center">{{ $income->tracking }}</td>
            <td style="text-align:center">{{ $income->getBultos() }}</td>
            <td style="text-align:center">{{ $income->getTipoBultos() }}</td>
            <td style="text-align:center">{{ $income->getPesoNeto() }}</td>
            <td style="text-align:center">{{ $income->getPesoBruto() }}</td>
        </tr>
    </table>
</div>

{{ $body }}
    
</body>
</html>