<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Consignatarios</title>
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 11px;
            text-align: center;
        }

        h1 {
            text-transform: uppercase;
            font-size: 35px;
        }

        .logo img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            float: left;
            margin-top: -10px;
            margin-left: 4px;
            border-radius: 300%;
        }

        .datos {
            text-align: left;
        }

        .datosVenta {
            margin-top: -10px;
        }

        .datos p {
            border-bottom: solid rgb(231, 231, 231) 0.5px;
            padding: 4px;
        }

        .table1 {
            width: 100%;
        }

        .table2 {
            margin-bottom: -18px;
        }

        .table2 td {
            padding: 6px;
            border-bottom: solid rgb(231, 231, 231) 0.5px;
            /* background: rgb(231, 231, 231); */
        }


        .datos h2 {
            background: rgb(0, 150, 250);
            color: white;
            font-size: 1em;
            text-align: center;
            padding: 10px 0;
        }

        .cabeza {
            margin-top: 28px;
        }

        .tabla3,
        .tabla4,
        .table5 {
            width: 100%;
        }

        .tabla3 td,
        .tabla4 td {
            padding: 6px;
            border-bottom: solid rgb(231, 231, 231) 0.5px;
        }

        .titulo__cliente,
        .titulo__venta {
            background: rgb(7, 84, 104);
            color: white;
            font-size: 1em;
            text-align: center;
            padding: 10px 0;
            width: 100%;
        }

        .titulo__cliente {
            text-align: left;
            padding-left: 10px;
        }

        .table5 {
            background: rgb(240, 240, 240);
        }

        .precioTotal {
            font-size: 1.2em;
            font-weight: bold;
            background: rgb(60, 129, 46);
            color: rgb(255, 255, 255);
        }

        .title {
            font-weight: 700;
        }

        .subtabla {
            background: rgb(221, 221, 221);
        }

        .centro {
            text-align: center;
        }

        .mensaje {
            font-style: italic;
            color: rgb(78, 78, 78);
        }

        h2.title {
            background: rgb(226, 226, 226);
            padding: 10px;
            color: rgb(88, 88, 88);
            font-size: 1.2em;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    @foreach ($empresa as $key => $dat)
        <div class="logo">

            <?php
        try { ?>
            <img src="{{ asset("foto/empresa/$dat->foto") }}" alt="">
            <?php } catch (\Exception $e) {
        }
        ?>
        </div>
        <h1>{{ $dat->nombre }}</h1>
        <div class="cabeza">
            <table class="table1">
                <tr>
                    <td class="table02">
                        <table class="table2">
                            <tr>
                                <td class="title">Ubicación</td>
                                <td>{{ $dat->ubicacion }}</td>
                            </tr>
                            <tr>
                                <td class="title">Teléfono</td>
                                <td>{{ $dat->telefono }}</td>
                            </tr>
                            <tr>
                                <td class="title">Correo</td>
                                <td>{{ $dat->correo }}</td>
                            </tr>
                            <tr>
                                <td class="title">Descripción</td>
                                <td>{{ $dat->descripcion }}</td>
                            </tr>
                            <tr>
                                <td class="title">Fecha</td>
                                <td><?= date('d-m-Y h:i:s') ?></td>
                            </tr>
                        </table>
                    </td>



                </tr>
            </table>


        </div>
    @endforeach

    <h2 class="title">REPORTE DE CONSIGNATARIOS</h2>
    <div class="datosVenta">
        <table class="tabla4">
            <tr>
                <td style="padding: 0">
                    <h2 class="titulo__venta">N°</h2>
                </td>
                <td style="padding: 0">
                    <h2 class="titulo__venta">DNI</h2>
                </td>
                <td style="padding: 0">
                    <h2 class="titulo__venta">NOMBRES DEL CONSIGNATARIO</h2>
                </td>
                <td style="padding: 0">
                    <h2 class="titulo__venta">DIRECCION</h2>
                </td>
                <td style="padding: 0">
                    <h2 class="titulo__venta">TELEFONO</h2>
                </td>

            </tr>
            @foreach ($datos as $key => $item)
                <tr>
                    <td class="centro">{{ $key + 1 }}</td>
                    <td class="centro">{{ $item->dni }}</td>
                    <td class="centro">{{ $item->nombre_razon_social }}</td>
                    <td class="centro">{{ $item->direccion }}</td>
                    <td class="centro">{{ $item->telefono }}</td>
                </tr>
            @endforeach
        </table>
    </div>



</body>

</html>
