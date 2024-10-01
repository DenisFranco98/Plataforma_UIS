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

// Obtener el ID del usuario desde la sesión
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die("Error: Usuario no identificado.");
}

// Consultar los datos del usuario
$query = "SELECT descripcion, justificacion, objetivos, modalidad,  FROM registros WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $descripcion = $row['descripcion'];
    $justificacion = $row['justificacion'];
    $objetivos = $row['objetivos'];
    $modalidad = $row['modalidad'];
} else {
    $descripcion = '';
    $justificacion = '';
    $objetivos = '';
    $modalidad = '';
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descripcion del Proyecto</title>
    <style>
        /* Aquí va tu CSS existente */
        .popup {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
        }
        .popup.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="profile.php">Perfil de Usuario</a>
            <a href="trabajo_grado.php">Trabajo de Grado</a>
            <a href="Informacion_trabajo.html">Descripción</a>
            <a href="https://sites.google.com/e3t.uis.edu.co/carteradetemas?authuser=0" target=“_blank”>Cartera de proyectos</a>
        </div>
        <div class="content">
            <nav>
                <h2>Detalles del proyecto</h2>
            </nav>
            <div class="conteiner-form">
                <form id="proyectoForm" action="guardar_proyecto.php" method="post">
                    <label for="descripcion">Descripción:</label><br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50"><?php echo htmlspecialchars($descripcion); ?></textarea><br><br>
                    
                    <label for="justificacion">Justificación:</label><br>
                    <textarea id="justificacion" name="justificacion" rows="4" cols="50"><?php echo htmlspecialchars($justificacion); ?></textarea><br><br>
                    
                    <label for="objetivos">Objetivos:</label><br>
                    <textarea id="objetivos" name="objetivos" rows="4" cols="50"><?php echo htmlspecialchars($objetivos); ?></textarea><br><br>
                    
                    <label for="modalidad">Modalidad:</label><br>
                    <textarea id="modalidad" name="modalidad" rows="4" cols="50"><?php echo htmlspecialchars($modalidad); ?></textarea><br><br>
                    
                    <input type="submit" value="Guardar">
                </form>
                <div id="popup" class="popup">Registro actualizado exitosamente</div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('proyectoForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar que el formulario se envíe de la manera tradicional

            var form = event.target;
            var formData = new FormData(form);

            fetch('guardar_proyecto.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    showPopup(data.message);
                    localStorage.setItem('descripcion', formData.get('descripcion'));
                    localStorage.setItem('justificacion', formData.get('justificacion'));
                    localStorage.setItem('objetivos', formData.get('objetivos'));
                    localStorage.setItem('modalidad', formData.get('modalidad'));
                } else {
                    showPopup('Error: ' + data.message);
                }
            }).catch(error => {
                console.error('Error:', error);
                showPopup('Error al enviar el formulario');
            });
        });

        function showPopup(message) {
            var popup = document.getElementById('popup');
            popup.textContent = message;
            popup.classList.add('show');
            setTimeout(function() {
                popup.classList.remove('show');
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('descripcion')) {
                document.getElementById('descripcion').value = localStorage.getItem('descripcion');
            }
            if (localStorage.getItem('justificacion')) {
                document.getElementById('justificacion').value = localStorage.getItem('justificacion');
            }
            if (localStorage.getItem('objetivos')) {
                document.getElementById('objetivos').value = localStorage.getItem('objetivos');
            }
            if (localStorage.getItem('modalidad')) {
                document.getElementById('modalidad').value = localStorage.getItem('modalidad');
            }
        });
    </script>
</body>
</html>

