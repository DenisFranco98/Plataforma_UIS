<?php
$host = 'localhost'; // Cambia esto si es necesario
$user = 'root';
$password = "E3t2023uis";
$database = 'usuarios'; // Nombre correcto de tu base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar búsqueda
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Consulta para obtener los registros excluyendo al usuario administrador
$sql = "SELECT * FROM registros WHERE 
        (ID LIKE '%$search%' OR 
        nombre LIKE '%$search%' OR 
        companero_proyecto LIKE '%$search%' OR 
        programa LIKE '%$search%' OR 
        titulo_proyecto LIKE '%$search%' OR 
        lider LIKE '%$search%' OR 
        tema_proyecto LIKE '%$search%' OR 
        plan LIKE '%$search%' OR 
        Anexos LIKE '%$search%' OR 
        Libro LIKE '%$search%' OR 
        Video LIKE '%$search%' OR 
        Pagina LIKE '%$search%' OR 
        usuario LIKE '%$search%')
        AND usuario != 'e3t.uis2023@gmail.com'";

$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../ejemplo_base/logo_uis.png" type="image/png"> <!-- Ajusta la ruta y tipo según tu imagen -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Usuarios</title>
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;            
            background-color: #f1f1f5b6;
        }
        nav {
            background-color: #333;
            color: white;
            margin-top: 15px;
            margin-left: 20px;
            margin-right: 20px;
            border-radius: 10px;
            font-size: 20px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 10px 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            padding: 15px;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 20px 0;
            padding: 10px;
            text-align: center;
            background-color: #444;
            border-radius: 15px;
        }
        .sidebar a:hover {
            background-color: #555;
        }
        .main-content {
            margin-left: 220px; /* ancho de la barra lateral + margen */
            padding: 20px;
            flex-grow: 1;
        }
        table {
            width: 100%;
            cursor: default !important;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: bold;
            border-collapse: collapse;
            box-shadow: 3px 4px 8px rgba(0, 0, 0);
        }
        .table-container {
            padding: 44px;
            overflow: auto;
        }
        th, td {
            border: 1px solid black;
            background-color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            white-space: nowrap; /* Evitar salto de línea */
            overflow: hidden; /* Ocultar contenido que desborde */
            text-overflow: ellipsis;
        }
        th {
            background-color: #1ba71bde;
            color: white;
            text-align: center;
        }
        td {
            white-space: nowrap; /* Evita el salto de línea en las celdas */
        }
        tr:hover td {
            background-color: rgba(99, 98, 98, 0.2); /* Cambio de color para todas las celdas de la fila al pasar el mouse */
        }
        .search-container {
            margin-bottom: 20px;
            max-width: 70%;
        }
        .barra-search {
            width: 40% !important;
            padding-left: 10px;
            margin-right: 10px;
            margin-left: 20px;
            height: 35px;
            border: none;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .barra-search:focus {
            outline: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0); 
        }
        .boton-con-imagen {
            width: 30px;
            height: 30px;
            background: transparent;
            background-image: url('search.png');
            background-size: contain;
            background-repeat: no-repeat;
            border: none;
            cursor: pointer;
            color: transparent;
            font-size: 0;
        }
        .btn {   
            margin-top: auto;      
            height: 20px;   
            width: 110px;
            padding: 5px 7px;
            border: none;
            border-radius: 5px;
            background-color: #1ba71b;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            text-align: center;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.02);
            background-color: #1ba71b;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="ver_registros.php">Base de datos</a>
        <a href="calificaciones.php">Calificaciones</a>
        <a href="estudiantes.php">Estudiantes</a>
        <a href="logout.php" style="background-color: #1ba71b;" >Cerrar sesión</a>
    </div>
    <div class="main-content">
        <nav>
            <h1>Registros de la Tabla</h1>
        </nav>
        
        <div class="search-container">
            <form class="formulario-search" method="POST">
                <input class="barra-search" type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar..." />
                <input type="submit" value="Buscar" class="boton-con-imagen">
            </form>
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre Estudiante 1</th>
                    <th>Nombre Estudiante 2</th>
                    <th>Correo Electrónico</th>
                    <th>Programa</th>
                    <th>Título del Proyecto</th>
                    <th>Docente Lider</th>
                    <th>Tema del Proyecto</th>
                    <th>Plan</th>
                    <th>Pagina</th>
                    <th>Libro</th>
                    <th>Video</th>
                    <th>Anexos</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Mostrar enlaces con texto personalizado
                        $linktema = $row['tema_proyecto'] ? "<a href='" . htmlspecialchars($row['tema_proyecto']) . "' target='_blank'>Link del tema del proyecto</a>" : "No disponible";
                        $linkPlan = $row['plan'] ? "<a href='" . htmlspecialchars($row['plan']) . "' target='_blank'>Link del Plan</a>" : "No disponible";
                        $linkAnexos = $row['Anexos'] ? "<a href='" . htmlspecialchars($row['Anexos']) . "' target='_blank'>Link de Anexos</a>" : "No disponible";
                        $linkLibro = $row['Libro'] ? "<a href='" . htmlspecialchars($row['Libro']) . "' target='_blank'>Link del Libro</a>" : "No disponible";
                        $linkVideo = $row['Video'] ? "<a href='" . htmlspecialchars($row['Video']) . "' target='_blank'>Link del Video</a>" : "No disponible";
                        $linkPagina = $row['Pagina'] ? "<a href='" . htmlspecialchars($row['Pagina']) . "' target='_blank'>Link de la Pagina</a>" : "No disponible";

                        echo "<tr>
                                <td>{$row['ID']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['companero_proyecto']}</td>
                                <td>{$row['usuario']}</td>
                                <td>{$row['programa']}</td>
                                <td>{$row['titulo_proyecto']}</td>
                                <td>{$row['lider']}</td>
                                <td>{$linktema}</td>
                                <td>{$linkPlan}</td>
                                <td>{$linkPagina}</td>
                                <td>{$linkLibro}</td>
                                <td>{$linkVideo}</td>
                                <td>{$linkAnexos}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No se encontraron registros</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <img src="logoEscuela.png" alt="Logo Escuela" style="position: absolute; top: 40px; right: 60px; width: 100px; z-index: 1000;">

</body>
</html>

<?php
$conn->close();
?>

