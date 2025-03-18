<?php
    session_start(); // Inicia la sesión
    if (!isset($_SESSION['categoria']) || $_SESSION['categoria'] !== 'admin') { // Si la categoría no es admin, redirige a login.php
        header("Location: login.php");
        exit();
    }
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
            background-color: red;
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
            var newRow = table.insertRow();
            newRow.setAttribute('accion', 'agregar'); // Marca la fila con la acción agregar
            var columns = table.rows[1].cells.length - 1; // Número de columnas sin contar la columna de acciones
            var actionsCellLeft = newRow.insertCell(); // Celda para el botón Guardar
            actionsCellLeft.style.border = 'none';
            actionsCellLeft.innerHTML = '<button onclick="guardar(this.parentNode.parentNode)">Guardar</button>';
            for (var i = 0; i < columns - 1; i++) { // Agrega celdas a la nueva fila
                var cell = newRow.insertCell(); // Celda para el contenido
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
        <h1>Panel de Administración</h1>
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
                        echo "<li><form method='get' style='display:inline;'><button type='submit' name='table' value='" . $row[0] . "' class='button'>" . $row[0] . "</button></form></li>";
                    }
                } else {
                    echo "No se encontraron tablas.";
                }
            ?>
        </ul>
        <?php
            if (isset($_GET['table'])) { // Muestra el contenido de la tabla seleccionada
                $table = $_GET['table']; 
                echo "<div id='editTable'>";
                echo "<h3 class='sub2'>Contenido de la tabla: $table <button onclick='editTable()'>Editar</button></h3>";
                
                $result = $conn->query("SHOW COLUMNS FROM $table");
                $id = $result->fetch_assoc()['Field'];

                $sql = "SELECT * FROM $table ORDER BY $id";
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