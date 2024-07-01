<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultados</title>
    <script src="https://kit.fontawesome.com/646ac4fad6.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos CSS */

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            border: none;
            height: 100vh;
            overflow: hidden;
        }

        .fondo {
            height: 100vh;
            background-image: url("{{ asset('img-inicio/img.jpg') }}");
            background-size: cover;
        }

        .ticket {
            width: 550px;
            /* border: 1px solid #ccc; */
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            margin: 0px auto;
            padding: 20px;
            height: 95vh;
            overflow: scroll;
            background: rgba(255, 255, 255, 0.884);
        }

        h1 {
            font-size: 35px;
            margin: 0;
            text-align: center;
            padding: 25px;
            background: rgb(8, 136, 187);
            color: rgb(238, 247, 252);
            text-shadow: 3px 3px 5px rgb(10, 31, 51);
        }

        h2 {
            font-size: 20px;
        }

        p {
            font-size: 18px;
            line-height: 1.5;
        }

        .ticket-info {
            display: flex;
            justify-content: space-between;
        }

        .ticket-info p {
            margin: 0
        }

        hr {
            color: rgb(199, 198, 198);
        }

        .verde {
            background: green;
            padding: 10px 8px;
            color: white;
        }

        .rojo {
            background: rgb(238, 24, 0);
            padding: 10px 8px;
            color: white;
        }

        .azul {
            background: rgb(0, 69, 207);
            padding: 10px 8px;
            color: white;
        }

        .naranja {
            background: rgb(237, 114, 0);
            padding: 10px 8px;
            color: white;
        }

        .ticket-info {
            text-align: center;
            margin-top: 20px;
        }

        .numero {
            background: black;
            color: white;
            padding: 5px 10px;
            font-size: 20px;
        }

        .descarga {
            background: rgb(81, 81, 81);
            padding: 8px 20px;
            color: white;
            text-decoration: none;
        }

        @media screen and (max-width:640px) {
            .ticket {
                width: 80%;
                overflow: scroll;
            }
        }
    </style>
</head>

<body>
    <div class="fondo">
        @foreach ($datos as $datos)
            <div class="ticket">
                <a href="{{ route('welcome') }}" class="descarga"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                @foreach ($empresa as $item)
                    <h1>{{ $item->nombre }}</h1>
                    <a class="numero">Ticket N° {{ $datos->numero_reg }}</a>
                    <hr>
                    {{-- <h2>DATOS DE LA EMPRESA</h2>
                    <p><b>Ubicacion:</b> {{ $item->ubicacion }}</p>
                    <p><b>Teléfono:</b> {{ $item->telefono }}</p>
                    <p><b>Correo:</b> {{ $item->correo }}</p> --}}
                @endforeach
                <hr>
                <h2>DATOS DEL REMITENTE</h2>
                <p><b>DNI:</b> {{ $datos->dniRemitente }}</p>
                <p><b>Nombre / Razon Social:</b> {{ $datos->nomRemitente }}</p>
                <p><b>Teléfono:</b> {{ $datos->remitenteTelefono }}</p>
                <p><b>Dirección:</b> {{ $datos->remitenteDireccion }}</p>
                <hr>
                <h2>DATOS DEL CONSIGNATARIO</h2>
                <p><b>DNI:</b> {{ $datos->dniReceptor }}</p>
                <p><b>Nombre / Razon Social:</b> {{ $datos->nomReceptor }}</p>
                <p><b>Teléfono:</b> {{ $datos->receptorTelefono }}</p>
                <p><b>Dirección:</b> {{ $datos->receptorDireccion }}</p>
                <hr>
                <h2>LUGAR DE PARTIDA / LLEGADA</h2>
                <p><b>Lugar de partida:</b>
                    {{ $datos->desde_distrito_nombre . ' - ' . $datos->desde_provincia_nombre . ' - ' . $datos->desde_departamento_nombre . ' - ' . $datos->desde_direccion }}
                </p>
                <p><b>Lugar de llegada:</b>
                    {{ $datos->hasta_distrito_nombre . ' - ' . $datos->hasta_provincia_nombre . ' - ' . $datos->hasta_departamento_nombre . ' - ' . $datos->hasta_direccion }}
                </p>
                <hr>
                <h2>DATOS DEL ENVÍO</h2>
                <p><b>Cantidad:</b> {{ $datos->cantidad }}</p>
                <p><b>Precio: S/.</b> {{ $datos->precio }}</p>
                <p><b>Estado de pago:</b>
                    @if ($datos->pago_estado == 1)
                        <a class="verde">PAGADO</a>
                    @else
                        <a class="rojo">NO PAGADO</a>
                    @endif
                </p>
                <p><b>Fecha de envío:</b> {{ $datos->fecha_salida }}</p>
                <p><b>Fecha de recojo:</b> {{ $datos->fecha_recojo }}</p>
                <p><b>Estado de Envío:</b>
                    @if ($datos->envio_estado == 1)
                        <a class="azul">{{ strtoupper($datos->nombre) }}</a>
                    @else
                        @if ($datos->envio_estado == 2)
                            <a class="naranja">{{ strtoupper($datos->nombre) }}</a>
                        @else
                            @if ($datos->envio_estado == 3)
                                <a class="rojo">{{ strtoupper($datos->nombre) }}</a>
                            @else
                                @if ($datos->envio_estado == 4)
                                    <a class="verde">{{ strtoupper($datos->nombre) }}</a>
                                @endif
                            @endif
                        @endif
                    @endif
                </p>
                <p><b>Descripcion:</b> {{ $datos->descripcion }}</p>
            </div>
        @endforeach
    </div>
</body>

</html>
