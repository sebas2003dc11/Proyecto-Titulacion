<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Formulario de Cambio de Contraseña</title>
    <style>
        form {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="password"] {
            display: block;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        .error-message {
            color: red;
        }
        a{
            display: block;
            text-align: right;
        }
    </style>
</head>

<body>
    <form action="{{ route('recuperar.update') }}" class="mt-5" method="post">
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

        @csrf
        <input hidden type="text" value="{{ request()->route('id') }}" name="txtid">
        <input hidden type="text" value="{{ request()->route('codigo') }}" name="txtcodigo">
        <label for="nueva_contraseña">Nueva Contraseña:</label>
        @error('nueva_contraseña')
            <p class="error-message">{{ $message }}</p>
        @enderror
        <input type="password" id="nueva_contraseña" name="nueva_contraseña">
        <label for="confirmar_contraseña">Confirmar Contraseña:</label>
        @error('confirmar_contraseña')
            <p class="error-message">{{ $message }}</p>
        @enderror
        <input type="password" id="confirmar_contraseña" name="confirmar_contraseña">
        <a href="{{ route('login') }}">Iniciar sesión</a>
        <input type="submit" value="Actualizar mi contraseña">
    </form>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
</body>

</html>
