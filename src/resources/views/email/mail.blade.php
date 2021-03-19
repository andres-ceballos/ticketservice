<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" />
</head>

<body>
    <style type="text/css">
        html {
            box-sizing: border-box;
            font-size: 62.5%;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        html {
            font-size: 62.5%;
        }

        .header,
        .footer {
            background-color: black;
            color: white;
            text-align: center;
            font-size: 3.6rem;
            font-weight: bold;
            padding: 2rem 0;
            text-transform: uppercase;
        }

        .container {
            width: 95%;
            max-width: 120rem;
            margin: 0 auto;
        }

        .main {
            margin: 2rem 0;
        }

        .main p {
            font-size: 1.8rem;
        }

        .incident-container {
            background-color: lavender;
        }

        .incident-content {
            padding: 2rem 3rem;
        }

        .main .title {
            font-weight: bold;
        }

        .btn {
            text-decoration: none;
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 1rem 3rem;
            margin-top: 3rem;
            text-align: center;
            border: none;
            flex: 0 0 100%;
        }

        .btn:hover {
            cursor: pointer;
        }

        .btn-blue {
            background-color: lightseagreen;
        }

        .footer {
            font-size: 3rem;
            padding: 0rem;
        }
    </style>

    <div class="header">
        Servicio de Incidencias
    </div>
    <div class="container">
        <div class="main">
            <p>Hola {{ $name }}, tu incidencia ha sido resuelta.</p> <br>
            <p>Detalles de la solicitud: </p>
            <div class="incident-container">
                <div class="incident-content">
                    <p class="title">{{ $title }}</p>
                    <p class="message">{{ $message_reply }} </p>
                </div>
            </div>
            <br>
            <p>Por favor, califica la atención del servicio dando click en el siguiente botón</p>
            <a href="{{ $rating_page }}" class="btn btn-blue">Calificar servicio</a>
            <br>
        </div>
    </div>
    <div class="footer">
        <p>Tickets Service</p>
    </div>
</body>

</html>