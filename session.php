<?php
    session_start(); // Inicia la sesión
    $conn = new mysqli("10.0.2.2", "root", "", "hospital"); // Crea la conexión
    
    if ($conn->connect_error) { // Verifica la conexión
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recoger datos del formulario
    $usuario = htmlspecialchars($_POST['usuario']);
    $contrasenya = htmlspecialchars($_POST['contrasenya']);

    // Verificar credenciales del doctor
    $stmt = $conn->prepare("SELECT id_persona, categoria FROM usuario WHERE login = ? AND contrasenya = ?");
    $stmt->bind_param("ss", $usuario, $contrasenya);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $id_persona = $row['id_persona'];
        $categoria = $row['categoria'];        
        $_SESSION['id_persona'] = $id_persona; // Guarda id_persona en la sesión
        $_SESSION['categoria'] = $categoria;
    
        // Redirigir según la categoría
        if ($categoria == 'doctor') {
            header("Location: doctor.php");
        } elseif ($categoria == 'paciente') {
            header("Location: paciente.php");
        } elseif ($categoria == 'admin') {
            header("Location: admin.php");
        } else {
            echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
                <div>Categoría no válida.</div>
            </div>";
        }
    } else {
        echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
            <div>Usuario o contraseña incorrecto.</div>
        </div>";    
    }    
    $stmt->close();
?>