<?php
session_start(); // Inicia la sesión

$host = 'localhost'; // Cambia esto si es necesario
$user = 'root';
$password = "E3t2023uis";
$database = 'usuarios';

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);


// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el usuario desde la sesión
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    die("Error: Usuario no identificado.");
}

// Obtener datos del formulario
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
$justificacion = isset($_POST['justificacion']) ? $_POST['justificacion'] : null;
$objetivos = isset($_POST['objetivos']) ? $_POST['objetivos'] : null;
$modalidad = isset($_POST['modalidad']) ? $_POST['modalidad'] : null;

// Obtener el ID del usuario basado en el nombre de usuario almacenado en la sesión
$query = "SELECT ID FROM registros WHERE usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if ($user_id) {
    // Construir la consulta de actualización
    $query = "UPDATE registros SET ";
    $params = [];
    $types = '';

    if ($descripcion !== null) {
        $query .= "descripcion = ?, ";
        $params[] = $descripcion;
        $types .= 's';
    }
    if ($justificacion !== null) {
        $query .= "justificacion = ?, ";
        $params[] = $justificacion;
        $types .= 's';
    }
    if ($objetivos !== null) {
        $query .= "objetivos = ?, ";
        $params[] = $objetivos;
        $types .= 's';
    }
    if ($modalidad !== null) {
        $query .= "modalidad = ?, ";
        $params[] = $modalidad;
        $types .= 's';
    }

    // Quitar la coma final y agregar la condición WHERE
    $query = rtrim($query, ', ') . " WHERE ID = ?";
    $params[] = $user_id;
    $types .= 'i';

    // Prepara y enlaza
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirige con un parámetro de éxito
        header("Location: informacion_trabajo.html?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar conexión
    $stmt->close();
} else {
    echo "Error: No se encontró el ID del usuario.";
}

$conn->close();
?>

