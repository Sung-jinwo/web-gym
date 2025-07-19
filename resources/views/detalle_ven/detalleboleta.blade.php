<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota de Venta - Ivonne Gym</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 65mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 0.9px dashed #000;
        }
        .logo {
            max-width: 150px;
            margin: 0 auto 5px;
        }
        .address {
            font-size: 10px;
            text-align: center;
            margin-bottom: 5px;
        }
        .title {
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            margin: 10px 0;
        }

        .client-info div {
            margin-bottom: 3px;
        }
        .client-info span {
            display: inline-block;
            width: 70px;
        }
        .products {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .products th {
            text-align: left;
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
        }
        .products td {
            padding: 10px 0;
        }
        .total {
            font-weight: bold;
            text-align: right;
            border-top: 0.9px dashed #000;
            border-bottom: 0.9px dashed #000;
            padding: 7px 0;
            margin: 10px 0;
        }
        .total-text {
            font-style: italic;
            text-align: center;
            margin-bottom: 10px;
        }
        .seller-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
            border-top: 0.9px dashed #000;
            padding-top: 10px;
        }
        .social-media {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .mostrar{
            display: inline-block;
            min-width: 150px;
            padding: 0 2px;
            position: relative;
            top: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="img/ivonnegym.png" alt="Logo Ivonne Gym" class="logo">
        <div class="address">
            {{$detalleventa->sede->sede_direccion}}– El Porvenir<br>
            Trujillo – La Libertad<br>
            {{$detalleventa->sede->sede_telefono}}
        </div>
    </div>

    <div class="header">
        <div class="title">Nota de Venta</div>
        <div class="title">NV 001-{{str_pad($detalle->id_detalle ?? '0', 6, '0', STR_PAD_LEFT)}}</div>
        <table style="width: 100%; border-collapse: collapse; margin: 2mm 0;">
            <tr>
                <td style="width: 50%; text-align: left; padding: 1mm 0;">FECHA: {{ $fecha_actual }}</td>
                <td style="width: 50%; text-align: right; padding: 1mm 0;">HORA: {{ $hora_actual }}</td>
            </tr>
        </table>
    </div>

    <div class="client-info">
        <div> <span>Cliente:</span>
            <div class="mostrar">
                @if ($detalleventa->alumno)
                    {{ $detalleventa->alumno->alum_nombre }}, {{$detalleventa->alumno->alum_apellido}}
                @else
                    Cliente no registrado
                @endif
            </div>
        </div>
        <div><span>Documento:</span>
            <div class="mostrar">
                @if ($detalleventa->alumno)
                    {{$detalleventa->alumno->alum_numDoc}}
                @else
                    Sin Numero de Documento
                @endif
            </div>
        </div>

        <div><span>Celular:</span>
            <div class="mostrar">
                @if ($detalleventa->alumno)
                    {{$detalleventa->alumno->alum_telefo}}
                @else
                    Sin Numero de Telefono
                @endif
            </div>
        </div>
    </div>

    <table class="products">
        <thead>
        <tr>
            <th>MEMBRESIA</th>
            <th style="text-align: center">CANT.</th>
            {{-- <th style="text-align: right;">IMPORTE</th> --}}
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Producto Comprado <br>{{$deatlleproducto->categoria->nombre}} x {{$deatlleproducto->prod_nombre}}</td>
            <td style="text-align: center">{{$detalle->datelle_cantidad}}</td>
            {{-- <td style="text-align: right;">S/{{ number_format($detalle->datelle_precio_unitario, 2) }}</td> --}}
        </tr>
        </tbody>
    </table>


    <div class="total">TOTAL: S/ {{ number_format($detalle->datelle_sub_total, 2) }}</div>
{{--    <div class="total-text">Ciento Sesenta y Cinco con 00/100 soles</div>--}}

    <div class="seller-info">
        <div> <span>Lugar De Venta: </span>  {{$detalleventa->sede->sede_nombre}}

        </div>
        <div> <span>Condición de Pago:</span>  {{$detalle->metodo->tipo_pago}}</div>
    </div>

    <div class="footer">
        <div style="font-weight: bold; margin-bottom: 5px;">GRACIAS POR SU COMPRA</div>
        <div>No se aceptan cambios ni devoluciones</div>
        <div>No hay devolución de dinero después de su compra</div>
    </div>

    <div class="social-media">
        Síguenos en Nuestras Redes Sociales:<br>
        Facebook - Instagram - TikTok
    </div>
</body>
</html>
