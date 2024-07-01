<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ asset('bootstrap4/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('inicio/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/fontawesome.min.css') }}">
    <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
    <title>Inicio de sesión</title>
    <style>
        #mi-formulario {
            display: none;
        }

        .title {
            font-size: 29px !important;
        }
    </style>
</head>

<body>
    <img class="wave" src="{{ asset('inicio/img/wave.png') }}">
    <div class="container">
        <div class="img">
            <img src="{{ asset('inicio/img/bg.svg') }}">
        </div>
        <div class="login-content">
            <form method="POST" action="{{ route('recuperar.enviarCorreo') }}">
                @csrf
                <img src="{{ asset('inicio/img/avatar.svg') }}">
                <h2 class="title">RECUPERAR CONTRASEÑA</h2>

                @if (session('CORRECTO'))
                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                        <small>{{ session('CORRECTO') }}</small>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                @if (session('INCORRECTO'))
                    <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                        <small>{{ session('INCORRECTO') }}</small>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                @error('usuario')
                    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                        <small>{{ $message }}</small>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @enderror
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Ingrese su usuario</h5>
                        <input required id="usuario" type="text"
                            class="input @error('usuario') is-invalid
                        @enderror" name="usuario"
                            title="Ingrese su usuario" value="{{ old('usuario') }}">
                    </div>
                </div>

                <div class="text-center">
                    <a class="font-italic isai5" href="{{ route('login') }}" id="mostrar-formulario">Iniciar sesión</a>
                </div>

                <input name="btningresar" class="btn" title="click para ingresar" type="submit" value="RESTABLECER">
                {{-- login --}}
            </form>


        </div>
    </div>
    <script type="text/javascript" src="{{ asset('inicio/js/main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('inicio/js/main2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bootstrap4/js/jquery.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('bootstrap4/js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bootstrap4/js/bootstrap.bundle.js') }}"></script>
    <script>
        // Selecciona el botón y el formulario
        const btnMostrar = document.querySelector("#mostrar-formulario");
        const formulario = document.querySelector("#mi-formulario");

        // Agrega un evento click al botón
        btnMostrar.addEventListener("click", function() {
            // Muestra el formulario
            formulario.style.display = "block";
        });
    </script>
</body>

</html>
