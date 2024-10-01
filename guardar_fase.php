<?php
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

// Obtener los datos enviados por AJAX
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$fase = isset($_POST['fase']) ? (int)$_POST['fase'] : 0;
$item1 = isset($_POST['item1']) ? $_POST['item1'] : '';
$item2 = isset($_POST['item2']) ? $_POST['item2'] : '';
$item3 = isset($_POST['item3']) ? $_POST['item3'] : '';
$item4 = isset($_POST['item4']) ? $_POST['item4'] : '';
$item5 = isset($_POST['item5']) ? $_POST['item5'] : '';
$item6 = isset($_POST['item6']) ? $_POST['item6'] : '';
$item7 = isset($_POST['item7']) ? $_POST['item7'] : '';
$item8 = isset($_POST['item8']) ? $_POST['item8'] : '';
$item9 = isset($_POST['item9']) ? $_POST['item9'] : '';
$item10 = isset($_POST['item10']) ? $_POST['item10'] : '';
$item11 = isset($_POST['item11']) ? $_POST['item11'] : '';
$item12 = isset($_POST['item12']) ? $_POST['item12'] : '';
$observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';

if ($id > 0) {
    // Preparar la consulta para actualizar la base de datos
    $sql = "UPDATE registros SET 
                fase = ?, 
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
            WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssi", $fase, $item1, $item2,  $item3, $item4, $item5, $item6,  $item7, $item8, $item9, $item10, $item11, $item12,$observaciones, $id);

    if ($stmt->execute()) {
        echo "Datos guardados correctamente.";
    } else {
        echo "Error al guardar los datos: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Datos inválidos.";
}

$conn->close();
?>