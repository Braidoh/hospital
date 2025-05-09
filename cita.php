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

        input[type="number"],
        input[type="text"],
        input[type="time"],
        input[type="date"] {
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
        input[name="enviar"] {
            float: right;
        }
    </style>
    <script>
        function validateForm(action) {
            if (action === 'enviar') {
                paciente.required = true;
                dotor.required = true;
                fecha.required = true;
                hora.required = true;
                motivo.required = true;
            } else {
                return false; // No se envía el formulario
            }
        }
    </script>
</head>
<body>
    <div class="container"> 
        <form action="cita.php" method="post">
            <h1>Cita médica</h1>
            <br>
            <label for="paciente">ID paciente:</label>
            <input type="number" id="paciente" name="paciente" min="1" required>
            <label for="doctor">ID Doctor:</label>
            <input type="number" id="doctor" name="doctor" min="1" required>
            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>">
            <label for="motivo">Motivo:</label>
            <input type="text" id="motivo" name="motivo">
            <br>
            <input type="submit" name="inicio" value="Inicio" onclick="window.location.href='inicio.php'; return false;">
            <input type="submit" name="enviar" value="Enviar" onclick="validateForm('enviar')">
        </form>
    </div>
</body>
</html>

<?php
    $conn = new mysqli("10.0.2.2", "root", "1234", "hospital"); // Crea la conexión

    if ($conn->connect_error) { // Verifica la conexión
        die("Conexión fallida: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Quan s'hagi enviat el formulari
        // Recoger datos del formulario
        $paciente = htmlspecialchars($_POST['paciente']);
        $doctor = htmlspecialchars($_POST['doctor']);
        $hora = htmlspecialchars($_POST['hora']);
        $fecha = htmlspecialchars($_POST['fecha']);
        $motivo = htmlspecialchars($_POST['motivo']);

        $datetime = $fecha . ' ' . $hora; // Concatena la fecha y la hora
        
        $result = $conn->query("SELECT MAX(id_cita) AS max_id FROM cita"); // Obtiene el ID de la última cita
        $row = $result->fetch_assoc(); // Obtiene la fila
        $id_cita = $row['max_id'] + 1; // Suma +1 al ID de la última cita

        $stmt = $conn->prepare("INSERT INTO cita VALUES (?, ?, ?, ?, ?, NULL)"); // Prepara la sentencia
        $stmt->bind_param("iiiss", $id_cita, $paciente, $doctor, $datetime, $motivo); // Asigna los parámetros a la sentencia
        $stmt->execute(); // Ejecuta la sentencia
        $stmt->close(); // Cierra la sentencia
    }
    $conn->close(); // Cierra la conexión
?>