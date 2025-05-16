<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "1234";
$base_de_datos = "hospital";

$conn = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$estadoPlazas = [];
for ($i = 1; $i <= 12; $i++) {
    $sql = "SELECT 1 FROM ocupaciones_parking WHERE plaza_id = $i AND fecha_liberacion IS NULL LIMIT 1";
    $res = $conn->query($sql);
    $estadoPlazas[$i] = $res->num_rows > 0 ? 'occupied' : '';
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Simulador de Parking</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f0f2f5;
      margin: 0;
    }
    .parking-container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .parking-lot {
      display: grid;
      grid-template-columns: repeat(6, 80px);
      grid-gap: 20px 40px;
      row-gap: 80px;
    }
    .slot {
      width: 80px;
      height: 120px;
      background-color: #4CAF50;
      display: flex;
      justify-content: center;
      align-items: center;
      border: 2px solid #222;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      transition: background-color 0.3s;
    }
    .occupied {
      background-color: #D32F2F;
    }
  </style>
</head>
<body>
  <div class="parking-container">
    <div class="parking-lot">
      <?php
        for ($i = 1; $i <= 12; $i++) {
          echo "<div class='slot {$estadoPlazas[$i]}' id='slot{$i}'>$i</div>";
        }



mqtt_listener.php

<?php
require("phpMQTT.php"); // Asegúrate de tener esta clase disponible

$server = "10.0.1.5";
$port = 1883;
$username = ""; // si aplica
$password = "";
$client_id = "phpMQTT-listener";

$mqtt = new phpMQTT($server, $port, $client_id);

if(!$mqtt->connect(true, NULL, $username, $password)) {
    exit("No se pudo conectar al broker MQTT\n");
}

for ($i = 1; $i <= 12; $i++) {
    $mqtt->subscribe(["parking/plaza$i" => ["qos" => 0, "function" => "procesarMensaje"]]);
}

while($mqtt->proc()) {}

$mqtt->close();

function procesarMensaje($topic, $msg) {
    if (preg_match('/plaza(\d+)/', $topic, $match)) {
        $plaza = intval($match[1]);
        $estado = trim($msg); // "1" o "0"

        $conn = new mysqli("localhost", "root", "1234", "hospital");
        if ($conn->connect_error) {
            echo "Error DB: " . $conn->connect_error . "\n";
            return;
        }

        $plaza = $conn->real_escape_string($plaza);
        $matricula = ($estado === "1") ? generarMatricula() : "Desconocida";

        if ($estado === "1") {
            // Plaza ocupada
            $fecha_ocupacion = date("Y-m-d H:i:s");
            $sql = "INSERT INTO ocupaciones_parking (plaza_id, matricula, fecha_ocupacion, fecha_liberacion)
                    VALUES ('$plaza', '$matricula', '$fecha_ocupacion', NULL)";
        } else {
            // Plaza liberada
            $fecha_liberacion = date("Y-m-d H:i:s");
            $sql = "UPDATE ocupaciones_parking 
                    SET fecha_liberacion = '$fecha_liberacion', matricula = 'Desconocida'
                    WHERE plaza_id = '$plaza' AND fecha_liberacion IS NULL";
        }

        if ($conn->query($sql) === TRUE) {
            echo "Plaza $plaza actualizada: estado=$estado\n";
        } else {
            echo "Error en plaza $plaza: " . $conn->error . "\n";
        }

        $conn->close();
    }
}

function generarMatricula() {
    $letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $numeros = "0123456789";
    return substr(str_shuffle($letras), 0, 4) . substr(str_shuffle($numeros), 0, 4);
}


