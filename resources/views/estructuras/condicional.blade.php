<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estructuras de Control</title>
</head>
<body>
    <div>
        <h1>Estructuras de Control</h1>
        <h2>Su nota es: {{ $nota }}</h2>
        <p>
            Stuación:
            @if ($nota >= 10.5)
                Aprobado
            @else
                Desaprobado
            @endif
        </p>
        <p>
            Categoría:
            @if ($nota >= 0 && $nota < 3)
                Pésimo
            @elseif ($nota >= 3 && $nota < 6)
                Regular
            @elseif ($nota >= 6 && $nota < 9)
                Bueno
            @else
                Excelente
            @endif
        </p>
        <p>
            @php
                function sumar($num1, $num2)
                {
                    return $num1 + $num2;
                }
            @endphp
            Resultado: {{ sumar(10, 25) }}
        </p>
    </div>
</body>
</html>
