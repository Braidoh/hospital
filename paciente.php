<?php
    session_start(); // Inicia la sesión
    if (!isset($_SESSION['categoria']) || $_SESSION['categoria'] !== 'paciente') { // Si la categoría no es admin, redirige a login.php
        header("Location: login.php");
        exit();
    }
    $id_persona = $_SESSION['id_persona'];
?>
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
            background-color: #f4f4f4;
            text-align: center;
        }
        header {
            background-color: blue;
            color: white;
            padding: 5px;
            position: relative;
        }
        .container {
            margin-top: 50px;
        }
        button {       
            padding: 10px 20px;
            border: 2px solid black;
            border-radius: 5px;                      
            background-color: white;
            padding: 10px 15px;
            font-weight: bold;
        }            
        button:hover {
            background-color: lightgray;
            color: black;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 100%;
            max-width: 60%;
        }
        table, th, td {
            border: none;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid black;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            display: inline;
            margin-right: 10px;
        }
        .sub2 {
            margin-top: 3%;
        }
        .inicio {      
            margin-top: 10px;      
            position: absolute;
            top: 20%;
            left: 20px;
        }
        .save {            
            margin-top: 10px;
            position: absolute;
            top: 20%;
            right: 20px;            
        }
        .add-row {
            width: 80%;
            margin: 20px auto;
            padding: 10px;
            border: 2px dashed black;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 24px;
            display: none;
        }
        .editable {
            background-color: #f9f9f9;            
        }
        .actions {
            display: none;
            border-top: none;
            border-bottom: none;
            border-right: none;
            border-left: 1px solid black; /* Deja solo el borde izquierdo */
            width: 8%; 
        }
    </style>        
</head>
<body>
    <header>
        <button class="inicio" onclick="window.location.href='logout.php'">Inicio</button>
        <h1>Panel de Paciente</h1>
        <button class="save" onclick="Guardar()">Guardar</button>
    </header>
    <div class="container">
        <h3 id="tableHeader">Tablas de la Base de Datos</h3>
        <ul id="tableList">
            <?php
                $conn = new mysqli("localhost", "root", "", "hospital"); // Crea conexión
                
                if ($conn->connect_error) { // Verifica la conexión
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Obtiene las tablas de la base de datos
                $sql = "SHOW TABLES";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) { // Muestra las tablas
                    while($row = $result->fetch_array()) {
                        if (in_array($row[0], ['cita', 'historial', 'ficha_medica'])) {
                            echo "<li><form method='get' style='display:inline;'><input type='hidden' name='id' value='" . $id_persona . "'><button type='submit' name='table' value='" . $row[0] . "' class='button'>" . $row[0] . "</button></form></li>";
                        }
                    }
                } else {
                    echo "No se encontraron tablas.";
                }
            ?>
        </ul>
        <?php
            if (isset($_GET['table']) && isset($_GET['id'])) { // Muestra el contenido de la tabla seleccionada
                $table = $_GET['table'];
                $tablas_permitidas = ['historial', 'ficha_medica', 'cita'];
                if (!in_array($table, $tablas_permitidas)) {
                    echo '<div style="display: flex; justify-content: center; align-items: center; height: 65vh;">
                            <div style="background-color: #ffe6e6; border: 2px solid #ff4d4d; padding: 40px; border-radius: 10px; text-align: center; font-family: Arial, sans-serif; max-width: 600px;">
                                <h2 style="color: #cc0000;">ACCESO DENEGADO</h2>
                                <p style="font-size: 18px; margin-bottom: 20px;">
                                    Lo que puedas ver es en lo que puedes acceder,<br>
                                    no irás más allá de lo que te permita.<br><br>
                                    <strong>Tu intento de hackeo ha sido registrado</strong><br>
                                    y has sido incluido a la lista negra.<br>
                                    Tus registros están siendo enviados a la Unidad de Delitos Telemáticos de la Guardia Civil (departamento de policía que se encarga de investigar los delitos cibernéticos, incluido el hacking).
                                </p>
                                <p style="font-style: italic; color: #666;">— El equipo administrador del sistema</p>
                            </div>
                          </div>';
                    exit();
                }
                echo "<div id='editTable'>";                
                if ($table == 'historial') {
                    $sql = "SELECT NSS, nombre, apellido1, apellido2, fecha_nacimiento, grupo_sanguineo, alergias FROM persona, paciente, historial
                        WHERE persona.id_persona = paciente.id_persona AND persona.id_persona = historial.id_paciente AND persona.id_persona = $id_persona;";
                } else if ($table == 'ficha_medica') {
                    $sql = "SELECT * FROM ficha_medica WHERE paciente = $id_persona";
                } else if ($table == 'cita') {
                    $sql = "SELECT * FROM $table WHERE paciente = $id_persona";
                }
                $result = $conn->query($sql);

                if ($result->num_rows > 0) { // Muestra los datos de la tabla
                    echo "<table><tr>";
                    while ($fieldinfo = $result->fetch_field()) { // Obtiene los nombres de columnas
                        echo "<th>" . $fieldinfo->name . "</th>";
                    }
                    while($row = $result->fetch_assoc()) { // Obtiene las filas de la tabla
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No se encontraron datos en la tabla.";
                }
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>