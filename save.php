<?php
    session_start();
    $categoria = $_SESSION['categoria']; 

    $tablas_permitidas = [
        'admin' => ['persona', 'usuario', 'doctor', 'paciente', 'historial', 'ficha_medica', 'cita', 'beeper'], // Admin puede acceder a todo
        'doctor' => ['paciente', 'historial', 'ficha_medica', 'cita'], // Doctor solo a algunas tablas
        'default' => [] // Otros roles no pueden acceder a nada
    ];

    $conn = new mysqli("10.0.2.2", "root", "", "hospital"); // 

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $accion = $_GET['accion']; // Obtiene la acción a realizar
    $tabla = $_GET['tabla']; // Obtiene el nombre de la tabla    
    if (!in_array($tabla, $tablas_permitidas[$categoria] ?? $tablas_permitidas['default'])) {
        die("No tienes permiso para acceder a esta tabla.");
    }

    $id_fila = $_GET['id_fila']; // Obtiene el ID de la fila
    $valores = isset($_GET['valores']) ? explode("|", $_GET['valores']) : []; // Obtiene los valores de la fila
    
    $result = $conn->query("SHOW COLUMNS FROM $tabla"); // Obtiene las columnas de la tabla
    $id_columna = null; // null por defecto
    while ($row = $result->fetch_assoc()) { // Busca la columna con 'id' en el nombre
        if (strpos($row['Field'], 'id') !== false) {
            $id_columna = $row['Field'];
            break;
        }
    }

    function eliminarRegistro($conn, $tabla, $id_columna, $id_fila) { // Elimina un registro de la tabla
        $stmt = $conn->prepare("DELETE FROM $tabla WHERE $id_columna = ?");
        $stmt->bind_param("i", $id_fila);
        $stmt->execute();
        $stmt->close();
    }

    function agregarRegistro($conn, $tabla, $valores) { // Añade un nuevo registro a la tabla
        $placeholders = implode(", ", array_fill(0, count($valores), "?"));
        $stmt = $conn->prepare("INSERT INTO $tabla VALUES ($placeholders)");
        $types = str_repeat("s", count($valores));  // Todos los parámetros como string (modificar si hay otros tipos)
        $stmt->bind_param($types, ...$valores);
        $stmt->execute();
        $stmt->close();
    }

    try { // Manejo de excepciones
        $conn->begin_transaction(); // Inicia una transacción

        switch ($accion) { // Según el tipo de acción (delete, update, add)
            case 'borrar': // Elimina un registro            
                eliminarRegistro($conn, $tabla, $id_columna, $id_fila);
                break;
                
            case 'agregar': // Inserta un nuevo registro            
                agregarRegistro($conn, $tabla, $valores);
                break;
            
            case 'editar': // Actualiza los datos de la tabla
                eliminarRegistro($conn, $tabla, $id_columna, $id_fila);
                agregarRegistro($conn, $tabla, $valores);
                break;
                
            default:
                echo "Acción desconocida para el ID $id_fila.<br>";
                break;
        }

        $conn->commit(); // Confirma la transacción
    } catch (mysqli_sql_exception $e) {
        $conn->rollback(); // Revierte la transacción en caso de error
        echo "Error: " . $e->getMessage();
    }
    $conn->close();    
    header("Location: " . $_SERVER['HTTP_REFERER']); // Redirige a la página anterior
?>