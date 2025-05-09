<?php
    session_start(); // Inicia la sesión
    if (!isset($_SESSION['categoria']) || $_SESSION['categoria'] !== 'doctor') { // Si la categoría no es admin, redirige a login.php
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
            background-color: #4CAF50;
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
            border-collapse: collapse; // Fusiona los bordes de las celdas
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
        .add-row {                    
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
            border: none;            
            width: 8%;
        }
    </style>
    <script>
        function editTable() { // Muestra la tabla editable
            var tableList = document.getElementById('tableList');
            var tableHeader = document.getElementById('tableHeader');
            var editTable = document.getElementById('editTable');
            var addRowButton = document.querySelector('.add-row');
            var cells = document.querySelectorAll('#dataTable td');
            var actions = document.querySelectorAll('.actions');

            if (editTable.style.display === 'none' || editTable.style.display === '') {
                tableList.style.display = 'none';
                tableHeader.style.display = 'none';
                editTable.style.display = 'block';
                addRowButton.style.display = 'block';
                cells.forEach(cell => {
                    if (!cell.classList.contains('actions')) {
                        cell.contentEditable = 'true';
                    }
                });           
                actions.forEach(action => action.style.display = 'table-cell');
            } else {
                tableList.style.display = 'block';
                tableHeader.style.display = 'block';
                editTable.style.display = 'none';
                addRowButton.style.display = 'none';
                cells.forEach(cell => cell.contentEditable = 'false');
                actions.forEach(action => action.style.display = 'none');
            }
        }

        function agregar() { // Agrega una nueva fila a la tabla
            var table = document.getElementById('dataTable');
            var columns = table.rows[0].cells.length; // Obtiene el número de columnas desde el encabezado
            var newRow = table.insertRow(); // Inserta una nueva fila
            newRow.setAttribute('accion', 'agregar'); // Marca la fila con la acción agregar
            var actionsCellLeft = newRow.insertCell(); // Celda para el botón Guardar
            actionsCellLeft.style.border = 'none';
            actionsCellLeft.innerHTML = '<button onclick="guardar(this.parentNode.parentNode)">Guardar</button>';
            for (var i = 0; i < columns - 1; i++) { // Agrega celdas vacías para las columnas de datos
                var cell = newRow.insertCell();
                cell.contentEditable = true; // Hace que el contenido sea editable
                cell.innerHTML = ''; // Limpia el contenido de la celda
            }
            var actionsCellRight = newRow.insertCell(); // Celda para el botón Borrar
            actionsCellRight.style.border = 'none';
            actionsCellRight.innerHTML = '<button onclick="eliminar(this.parentNode.parentNode)">Borrar</button>';
        }  

        function eliminar(row) { // Borra una fila de la tabla
            var tabla = "<?php echo $_GET['table']; ?>";            
            var id_fila = row.cells[1].innerText; // Obtiene el índice de la fila
            window.location.href = 'save.php?accion=borrar&tabla=' + tabla + '&id_fila=' + id_fila; // Redirige a save.php con los datos }
        }

        function guardar(row) { // Envia los cambios a save.php
            var accion = row.getAttribute('accion') || 'editar'; // Obtiene la acción de la fila, si es null, es editar
            var tabla = "<?php echo $_GET['table']; ?>";
            var id_fila = row.cells[1].innerText; // Obtiene el índice de la fila
            var valores = Array.from(row.cells).slice(1, -1).map(cell => cell.innerText); // Obtiene los valores de las columnas
            window.location.href = 'save.php?accion=' + accion + '&tabla=' + tabla + '&id_fila=' + id_fila + '&valores=' + valores.join('|');
        }
    </script>
</head>
<body>
    <header>
        <button class="inicio" onclick="window.location.href='logout.php'">Inicio</button>
        <h1>Pagina de Doctor</h1>
    </header>
    <div class="container">
        <h3 id="tableHeader">Tablas de la Base de Datos</h3>
        <ul id="tableList">
            <?php
                $conn = new mysqli("10.0.2.2", "root", "1234", "hospital"); // Crea conexión
                
                if ($conn->connect_error) { // Verifica la conexión
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Obtiene las tablas de la base de datos
                $sql = "SHOW TABLES";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) { // Muestra las tablas
                    while($row = $result->fetch_array()) {
                        if (in_array($row[0], ['persona', 'paciente', 'historial', 'ficha_medica', 'cita'])) {
                            echo "<li><form method='get' style='display:inline;'><input type='hidden' name='id' value='" . $id_persona . "'><button type='submit' name='table' value='" . $row[0] . "' class='button'>" . $row[0] . "</button></form></li>";
                        }                    
                    }
                } else {
                    echo "No se encontraron tablas.";
                }
            ?>
        </ul>
        <?php
            if (isset($_GET['table'])) { // Muestra el contenido de la tabla seleccionada
                $table = $_GET['table'];
                $tablas_permitidas = ['paciente', 'persona', 'historial', 'ficha_medica', 'cita'];
                if (!in_array($table, $tablas_permitidas)) {
                    include 'blacklist.php';
                    Blacklist($_SESSION['usuario'], $_SESSION['categoria'], $table); // Registra el intento de acceso no autorizado
                    echo '<div style="display: flex; justify-content: center; align-items: center; height: 65vh;">
                            <div style="background-color: #ffe6e6; border: 2px solid #ff4d4d; padding: 40px; border-radius: 10px; text-align: center; font-family: Arial, sans-serif; max-width: 600px;">
                                <h2 style="color: #cc0000;">ACCESO DENEGADO</h2>
                                <p style="font-size: 18px; margin-bottom: 20px;">
                                    Lo que puedas ver es en lo que puedes acceder,<br>
                                    no irás más allá de lo que te permita.<br><br>
                                    <strong>Tu intento de hackeo ha sido registrado</strong><br>
                                    y has sido incluido a la lista negra.<br>
                                    La oficina de supervisión ha sido notificada.
                                </p>
                                <p style="font-style: italic; color: #666;">— El equipo administrador del sistema</p>
                                <button onclick="window.location.href=\'logout.php\'" style="margin-top: 20px; padding: 10px 20px; background-color: #cc0000; color: white; border: none; border-radius: 5px; cursor: pointer;">Cerrar sesión</button>
                            </div>
                          </div>';
                    exit();
                }
                
                echo "<div id='editTable'>";
                echo "<h3 class='sub2'>Contenido de la tabla: $table <button onclick='editTable()'>Editar</button></h3>";
                
                $result = $conn->query("SHOW COLUMNS FROM $table");
                $id = $result->fetch_assoc()['Field'];

                if ($table == 'cita') {
                    $sql = "SELECT id_cita, fecha, CONCAT(nombre, ' ', apellido1, ' ', apellido2) AS paciente, motivo, observaciones 
                    FROM cita, persona WHERE cita.doctor = persona.id_persona AND cita.doctor = $id_persona";
                } else {
                    $sql = "SELECT * FROM $table ORDER BY $id";
                }

                $result = $conn->query($sql);
                echo "<table id='dataTable'><tr>";
                echo "<th class='actions'></th>"; // Añade una columna para las acciones
                while ($fieldinfo = $result->fetch_field()) { // Obtiene los nombres de columnas
                    echo "<th>" . $fieldinfo->name . "</th>";
                }
                echo "</tr>";
                while($row = $result->fetch_assoc()) { // Obtiene las filas de la tabla
                    echo "<tr>";
                    echo "<td class='actions guardar'><button onclick='guardar(this.parentNode.parentNode)'>Guardar</button></td>";
                    foreach ($row as $value) {
                        echo "<td class='editable' contenteditable='false'>" . $value . "</td>";
                    }
                    echo "<td class='actions'><button onclick='eliminar(this.parentNode.parentNode)'>Borrar</button></td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<div class='add-row' style='width:80%;' onclick='agregar()'>+</div>";                
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>