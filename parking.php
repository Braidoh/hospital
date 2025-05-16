<?php
// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "1234";
$base_de_datos = "hospital";

$conn = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta plazas ocupadas (fecha_liberacion es NULL)
$sql = "SELECT plaza_id FROM ocupaciones_parking WHERE fecha_liberacion IS NULL";
$result = $conn->query($sql);

$ocupadas = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $ocupadas[] = intval($row['plaza_id']);
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Estado del Parking</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Refresca automáticamente cada 10 segundos -->
  <meta http-equiv="refresh" content="10">
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
      background-color: #4CAF50; /* libre */
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
      background-color: #D32F2F; /* ocupado */
    }
  </style>
</head>
<body>
  <div class="parking-container">
    <h2 style="text-align:center; margin-bottom:20px;">Estado Actual del Parking</h2>
    <div class="parking-lot" id="parkingLot">
      <?php
        for ($i = 1; $i <= 12; $i++) {
            $class = in_array($i, $ocupadas) ? "slot occupied" : "slot";
            echo "<div class=\"$class\" id=\"slot$i\">$i</div>";
        }
      ?>
    </div>
  </div>
</body>
</html>
