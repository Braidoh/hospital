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


