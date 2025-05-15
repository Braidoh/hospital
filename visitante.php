<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <title>Visitante</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 5px;
            position: relative;
            text-align: center;
        }
        .button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: white;
            color: #4CAF50;
            border: 2px solid #4CAF50;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }            
        .button:hover {
            background-color: lightgray;
            color: black;
        }
        .container {
            margin-top: 10%;
            display: flex;
            justify-content: center;
            align-items: center;
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
        input[name="inicio"]:hover {
            background-color: lightgray;
        }
        input[name="inicio"] {
            background-color: white;
            color: black;                              
            border: 1px solid black;
            
        }
        input[name="buscar"] {
            float: right;
        }
        .resultado {
            margin-top: 10%;
            padding: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hospital Ada Lovelace</h1>
    </header>
    <div class="container">             
        <form action="" method="post">
            <label for="id">ID del paciente que quieres buscar:</label>
            <input type="number" id="id" name="id">
            <input type="submit" name="inicio" value="Inicio" onclick="window.location.href='inicio.php'; return false;">
            <input type="submit" name="buscar" value="Buscar">
        </form>
    </div>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $conn = new mysqli("10.0.2.2", "root", "1234", "hospital"); // Crea la conexión
            
            if ($conn->connect_error) {
                die("<div class='resultado'>Conexión fallida: " . $conn->connect_error . "</div>");
            }
            
            $id = $_POST['id']; // Obtener el ID del paciente
            $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que sea un número entero
            $id = $conn->real_escape_string($id); // Evitar inyección SQL
            
            $sql = "SELECT ubicacion, estado_ingresado FROM paciente WHERE id_persona = '$id'";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) { // Si hay resultados
                while ($row = $result->fetch_assoc()) // Obtener la fila de resultados
                if ($row['estado_ingresado'] == 'si') { // Si el paciente está ingresado
                    echo "<div class='resultado'>Estado del paciente: ingresado <br> Ubicación: " . $row['ubicacion'] . "</div>";
                } else { // Si el paciente no está ingresado
                    echo "<div class='resultado'>Estado del paciente: no ingresado</div>";
                }
            } else { // Si no hay resultados
                echo "<div class='resultado'>No se encontró ningún paciente con el ID proporcionado</div>";
            }
                     
            $conn->close();
        }
    ?>
</body>
</html>
