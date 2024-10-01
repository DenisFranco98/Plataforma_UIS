<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost'; // Cambia esto si es necesario
$user = 'root';
$password = "E3t2023uis";
$database = 'usuarios';
// Crear la conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Imprimir todos los parámetros recibidos
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $registro_id = $_POST['registro_id'] ?? '';
    $fase = $_POST['fase'] ?? '';

    if (!$registro_id || !$fase) {
        die("Faltan parámetros en la solicitud.");
    }

    // Recoger los datos del formulario
    $fase = $_POST['fase'] ?? '';
    $item1 = $_POST['item1'] ?? '';
    $item2 = $_POST['item2'] ?? '';
    $item3 = $_POST['item3'] ?? '';
    $item4 = $_POST['item4'] ?? '';
    $item5 = $_POST['item5'] ?? '';
    $item6 = $_POST['item6'] ?? '';
    $item7 = $_POST['item7'] ?? '';
    $item8 = $_POST['item8'] ?? '';
    $item9 = $_POST['item9'] ?? '';
    $item10 = $_POST['item10'] ?? '';
    $item11 = $_POST['item11'] ?? '';
    $item12 = $_POST['item12'] ?? ''; // item12 es opcional
    $comentario = $_POST['comentario'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';

    // Inicializar $stmt
    $stmt = null;
    $sql = "UPDATE registros SET fase = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $fase, $registro_id); 

    if ($stmt->execute()) {
        echo "Fase actualizada correctamente";
    } else {
        echo "Error al actualizar la fase: " . $conn->error;
    }

    if ($fase === '1') {
        // Preparar la consulta para fase 1
        $stmt = $conn->prepare("UPDATE registros SET 
            item1 = ?, 
            item2 = ?, 
            item3 = ?, 
            item4 = ?, 
            item5 = ?, 
            item6 = ?, 
            item7 = ?, 
            item8 = ?, 
            item9 = ?, 
            item10 = ?, 
            item11 = ?, 
            item12 = ?, 
            comentario = ?
            WHERE ID = ?");
        
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("ssssssssssssss", 
            $item1, $item2, $item3, $item4, $item5, $item6, 
            $item7, $item8, $item9, $item10, $item11, $item12, 
            $comentario, $registro_id);
    } else if ($fase === '2') {
        // Preparar la consulta para fase 2
        $stmt = $conn->prepare("UPDATE registros SET 
            item1 = ?, 
            item2 = ?, 
            item3 = ?, 
            item4 = ?, 
            item5 = ?, 
            item6 = ?, 
            item7 = ?, 
            item8 = ?, 
            item9 = ?, 
            item10 = ?, 
            item11 = ?, 
            item12 = ?, 
            observaciones = ? 
            WHERE ID = ?");
        
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("ssssssssssssss", 
            $item1, $item2, $item3, $item4, $item5, $item6, 
            $item7, $item8, $item9, $item10, $item11, $item12, 
             $observaciones, $registro_id);
    } else {
        die("Fase no válida.");
    }

    if ($stmt !== null) {
        // Ejecutar la consulta si $stmt está definido
        if ($stmt->execute()) {
            echo "Registro actualizado con éxito.";
        } else {
            echo "Error al actualizar el registro: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    }
    header("Location: calificaciones.php");
    exit();
    // Cerrar la conexión
$conn->close();
}
?>


