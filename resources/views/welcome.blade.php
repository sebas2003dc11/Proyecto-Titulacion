<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Inicio</title>
    <link rel="shortcut icon" href="{{ asset("foto/empresa/$foto") }}" type="image/x-icon">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
        }

        .fondo {
            width: 100%;
            height: 100vh;
            background-image: url("{{ asset('img-inicio/img.jpg') }}");
            background-size: cover;
        }

        h1 {
            text-align: center;
            font-size: 42px;
            color: rgb(255, 255, 255);
            padding: 10px;
            font-weight: bold;
        }


        div.container {
            width: 50%;
            max-width: 680px;
            min-width: 400px;
            background: rgba(2, 12, 31, 0.326);
            margin: auto;
            padding: 20px;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        input {
            padding: 20px;
            outline: none;
            font-size: 20px;
            font-style: italic;
        }

        input:focus {
            font-style: normal;
            font-weight: 500;
        }

        .entrada,
        .salida {
            padding: 17px 10px;
            outline: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            width: 100%;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
        }

        .entrada {
            background: rgb(0, 119, 199);
        }


        .entrada:hover {
            background: rgb(1, 99, 164);
        }

        p {
            text-align: center;
            font-size: 23px;
            font-weight: 500;
            color: rgb(190, 190, 190);
            margin-bottom: 0;
            margin-top: 0;
        }

        .login {
            font-style: italic;
            font-size: 20px;
            font-weight: 500;
            color: rgb(156, 156, 156);
        }

        .group__button {
            width: 100%;
            padding: 0;
            display: flex;

        }

        .marca {
            width: 100%;
            margin: 0;
            background: rgb(13, 39, 48);
            position: fixed;
            bottom: 0;
            z-index: 999;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .marca__texto {
            color: rgb(0, 162, 255);
            text-decoration: underline;
        }


        .error {
            color: red;
            font-size: 18px;
        }

        @media screen and (max-width:400px) {
            div.container {
                width: 100%;
                min-width: 100%;
            }
        }
    </style>


</head>

<body class="antialiased">
    <div class="fondo">
        <div class="container">
            <a class="login" href="{{ route('home') }}">Ingresar al sistema</a>
            @foreach ($empresa as $item)
                <h1>{{ $item->nombre }}</h1>
            @endforeach
            <form action="{{ route('buscar.buscarEnvioCliente') }}" method="get">
                <div class="form">
                    <p>Ingrese su Número de Registro</p>
                    <input required id="numero" autofocus type="number" name="numero"
                        placeholder="Número de ticket / registro">
                    @error('numero')
                        <p class="error">Ingrese su Número de Ticket / Registro</p>
                    @enderror
                    <div class="group__button">
                        <button id="entrada" class="entrada" type="submit">BUSCAR</button>
                    </div>
                    @if (session('INCORRECTO'))
                        <div class="alert alert-danger">{{ session('INCORRECTO') }}</div>
                    @endif

                </div>
            </form>
        </div>
    </div>
</body>

</html>
