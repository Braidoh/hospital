<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hospital Ada Lovelace</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: white;
                text-align: center;
            }
            header {
                background-color: #4CAF50;
                color: white;
                padding: 5px;
                position: relative;
            }
            .button {
                position: absolute;
                top: 20px;
                right: 20px;
                background-color: white;
                color: #4CAF50;
                border: 2px solid #4CAF50;
                padding: 10px 15px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .cita {
                right: 110px;
            }
            .button:hover {
                background-color: lightgray;
                color: black;
            }
            .container {
                margin-top: 50px;
                width: 100%;
                height: 100%;
            }
            .img {
                padding-top: 15%;
                width: 80%;                
                margin-top: 30px; /* Agregado para mover la imagen hacia abajo */
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Hospital Ada Lovelace</h1>
            <a href="login.php" class="button">Login</a>
            <a href="cita.php" class="button cita">Cita</a>
        </header>
        <div class="container">
            <h2>Nos preocupamos por tu salud</h2>
            <p>Brindamos atención médica de calidad con los mejores especialistas.</p>
            <img src="foto.jpg">
        </div>
    </body>
</html>