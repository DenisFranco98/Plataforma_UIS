<?php
session_start();
include 'db.php'; // Incluir archivo de conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

$usuario = $_SESSION['usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file-input"])) {
    $file = $_FILES["file-input"];
    
    // Verificar si se subió correctamente
    if ($file["error"] === UPLOAD_ERR_OK) {
        $fileName = $file["name"];
        $tempFilePath = $file["tmp_name"];
        
        // Leer el contenido del archivo
        $fileData = file_get_contents($tempFilePath);
        
        // Preparar y ejecutar la consulta para actualizar la imagen en la base de datos
        $sql = "UPDATE registros SET imagen_perfil = ? WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $fileData, $usuario);
        $stmt->execute();
        
        // Verificar si se ejecutó correctamente
        if ($stmt->affected_rows > 0) {
            echo "Imagen de perfil actualizada exitosamente.";
        } else {
            echo "Error al actualizar la imagen de perfil: " . $conn->error;
        }
        
        // Cerrar la consulta
        $stmt->close();
    } else {
        echo "Error al subir el archivo: " . $file["error"];
    }
}

// Consulta para obtener los datos del usuario
$sql_select = "SELECT * FROM registros WHERE usuario = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("s", $usuario);
$stmt_select->execute();
$result = $stmt_select->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <style>
        /* Estilos CSS como los mostrados previamente */
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="profile.php">Perfil de Usuario</a>
        <a href="work_grade.html">Trabajo de Grado</a>
    </div>
    <div class="content">
        <nav>
            <h1>Perfil de Usuario</h1>
        </nav>
        <div class="profile">
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file-input" id="file-input" style="display: none;">
                <img class="image" src="profile.jpg" alt="Foto de perfil" id="profile-pic">
                <br>
                <button type="button" class="edit-btn" onclick="openPhotoModal()">Cambiar foto</button>
                <button type="submit" style="display: none;" id="submit-btn">Guardar foto</button>
            </form>
        </div>
        <table border="1">
            <tr>
                <th>Campo</th>
                <th>Información</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="title-table">Código:</td>
                <td class="information"><?php echo $row['ID']; ?></td>
            </tr>
            <tr>
                <td class="title-table">Nombre:</td>
                <td class="information"><?php echo $row['nombre']; ?></td>
            </tr>
            <tr>
                <td class="title-table">Programa:</td>
                <td class="information"><?php echo $row['programa']; ?></td>
            </tr>
            <tr>
                <td class="title-table">Líder de Proyecto:</td>
                <td class="information"><?php echo $row['lider']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a class="btn" href="logout.php">Cerrar sesión</a>
    </div>
    <script>
        // Función para abrir el modal de selección de imagen
        function openPhotoModal() {
            document.getElementById('file-input').click();
        }

        // Evento cuando se selecciona una imagen
        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Mostrar el botón para guardar la imagen
                document.getElementById('submit-btn').style.display = 'block';
                // Mostrar la imagen en el perfil
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function() {
                    const imageDataUrl = reader.result;
                    document.getElementById('profile-pic').src = imageDataUrl;
                };
            }
        });
    </script>
</body>
</html>


