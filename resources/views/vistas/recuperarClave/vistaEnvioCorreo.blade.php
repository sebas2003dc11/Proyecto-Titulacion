<!DOCTYPE html>
<html>

<head>
    <title>Restablecer contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
        }

        h1 {
            margin-top: 50px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 40px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            color: white !important;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h1>Restablecer contraseña</h1>
    <p>Haga clic en el botón para restablecer su contraseña:</p>
    <button><a href="{{ route('recuperar.form', [$id_usuario, $codigo]) }}">Restablecer contraseña</a></button>
</body>

</html>
