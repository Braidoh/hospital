<?php
require('phpMQTT.php'); // Asegúrate de tener esta librería en tu directorio

$server = 'localhost';  // IP o hostname del broker
$port = 1883;           // Puerto MQTT
$username = '';         // Si tienes autenticación
$password = '';
$client_id = 'php-mqtt-listener';

$mqtt = new phpMQTT($server, $port, $client_id);

if (!$mqtt->connect(true, NULL, $username, $password)) {
    exit("No se pudo conectar al broker MQTT\n");
}

$topics = [
    'parking/plaza1' => ['qos' => 0, 'function' => 'procesarMensaje'],
    'parking/plaza2' => ['qos' => 0, 'function' => 'procesarMensaje']
];

$mqtt->subscribe($topics, 0);

while ($mqtt->proc()) {}

$mqtt->close();

function procesarMensaje($topic, $msg) {
    $plaza = explode('parking/plaza', $topic)[1];
    $estado = trim($msg);
    $matricula = ($estado === "1") ? generarMatricula() : 'Desconocida';

    $fecha_ocupacion = date("Y-m-d H:i:s");
    $fecha_liberacion = ($estado === "0") ? $fecha_ocupacion : null;

    $conn = new mysqli("localhost", "root", "1234", "hospital");
    if ($conn->connect_error) {
        error_log("DB Error: " . $conn->connect_error);
        return;
    }

    if ($estado === "1") {
        $stmt = $conn->prepare("INSERT INTO ocupaciones_parking (plaza_id, matricula, fecha_ocupacion, fecha_liberacion) VALUES (?, ?, ?, NULL)");
        $stmt->bind_param("sss", $plaza, $matricula, $fecha_ocupacion);
    } else {
        $stmt = $conn->prepare("UPDATE ocupaciones_parking SET fecha_liberacion = ?, matricula = 'Desconocida' WHERE plaza_id = ? AND fecha_liberacion IS NULL");
        $stmt->bind_param("ss", $fecha_liberacion, $plaza);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function generarMatricula() {
    $letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $numeros = "0123456789";
    $matricula = "";
    for ($i = 0; $i < 4; $i++) $matricula .= $letras[rand(0, 25)];
    for ($i = 0; $i < 4; $i++) $matricula .= $numeros[rand(0, 9)];
    return $matricula;
}
?>



