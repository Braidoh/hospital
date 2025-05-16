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
