<html>
<body>

<h1 style="background-color:#ffe499; text-align:center; color: black;">Entrada: {{ $income->getIncomeNumber() }}</h1>
<p>La entrada ya no está con estado 'On hold'!</p>
<p><strong>Cliente:</strong>{{ $income->customer->name }}</p>
    
</body>
</html>