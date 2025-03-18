<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulari</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            margin-top: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 25%;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;            
        }
        input[name="guest"]:hover {
            background-color: lightgray;
        }
        input[name="guest"] {
            background-color: white;
            color: black;                              
            border: 1px solid black;
            
        }
        input[name="enviar"] {
            float: right;
        }
    </style>
    <script>
        function validateForm(action) { 
            if (action === 'enviar') {
                usuario.required = true;
                contrasenya.required = true;
            } else {
                return false; // No se envía el formulario
            }
        }
    </script>
</head>
<body>
    <div class="container"> 
        <form action="session.php" method="post">
            <h1>LOG IN</h1>
            <p>Si tienes usuario inicia sesión.</p>
            <p>Si quieres ver a un paciente inicia como visitante.</p>
            <br>
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario">
            
            <label for="contrasenya">Contraseña:</label>
            <input type="text" id="contrasenya" name="contrasenya">

            <input type="submit" name="guest" value="Soy visitante" onclick="window.location.href='visitante.php'; return false;">
            <input type="submit" name="enviar" value="Enviar" onclick="validateForm('enviar');">
        </form>
    </div>
</body>
</html>