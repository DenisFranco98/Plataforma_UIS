<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

$usuario = $_SESSION['usuario'];
$sql = "SELECT * FROM registros WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../ejemplo_base/logo_uis.png" type="image/png"> <!-- Ajusta la ruta y tipo según tu imagen -->
    <title>Perfil de Usuario</title>
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('http://e3t.uis.edu.co/eisi/images/Grupos/G7/contenidos/imagenes/20191030130119-inicio.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            display: flex;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;            
            background-color: #f1f1f5b6;
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
        .content {
            margin-left: 200px;
            padding: 20px;
            width: calc(100% - 200px);
            transition: margin-left 0.3s, width 0.3s;
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
        .profile {
            text-align: center;
            margin-top: 20px;
        }
        .profile img {
            margin-top: 20px;
            margin-bottom: 15px;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }
        .profile img:hover {
            transform: scale(1.1);
        }
        .edit-btn {
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background-color: #1ba71b;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .edit-btn:hover {
            transform: scale(1.1);
            background-color: #279c2d;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 100%; /* Ajustado al ancho completo */
            max-width: 800px; /* Máximo ancho para evitar que se extienda demasiado */
            border-radius: 10px; /* Redondear bordes */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            background-color: #fff; /* Fondo blanco */
        }
        th, td {
            padding: 12px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            text-align: left; /* Alineación izquierda para el contenido */
        }
        table:hover {
            box-shadow: 0 9px 10px rgba(0, 0, 0, 0.1);
        }
        th {
            font-weight: bold;
            text-align: center !important;
            background-color: #1ba71b;
            color: #fff;
            border-radius: 5px 5px 0 0; /* Redondear esquinas superiores */
        }
        .title-table {
            font-weight: bold;
        }
        .information {
            text-align: center;
        }
        a {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
            font-size: 18px;
        }
        a:hover {
            color: #007BFF;
        }
        #cropperModal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            display: none;
            max-width: 400px; /* Limitar el ancho máximo del modal */
            max-height: 80vh; /* Limitar la altura máxima del modal */
            overflow: auto; /* Habilitar scroll si es necesario */
        }

        #image-cropper {
            max-width: 100%; /* Asegurarse de que la imagen no sobresalga */
            height: auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="profile.php">Perfil de Usuario</a>
        <a href="trabajo_grado.php">Trabajo de Grado</a>
        <a href="Informacion_trabajo.html">Descripción</a>
        <a href="https://sites.google.com/e3t.uis.edu.co/carteradetemas?authuser=0" target=“_blank”>Cartera de proyectos</a>
        <a href="logout.php" style="background-color: #1ba71b;">Cerrar sesión</a>
    </div>
    <div class="content">
        <nav>
            <h1>Perfil de Usuario</h1>
        </nav>
        <div class="profile">
            <input type="file" name="file-input" id="file-input" style="display: none;">
            <img class="image" src="imagen_perfil.jpg" alt="Foto de perfil" id="profile-pic">
            <br>
            <button type="button" class="edit-btn" onclick="openPhotoModal()">Cambiar foto</button>
        </div>

        <!-- Modal de Cropper.js -->
        <div id="cropperModal">
            <img id="image-cropper" src="" alt="Imagen para recortar">
            <br>
            <button id="crop-btn" class="edit-btn">Recortar y guardar</button>
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
    </div>

    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        let cropper;
        const fileInput = document.getElementById('file-input');
        const profilePic = document.getElementById('profile-pic');
        const cropperModal = document.getElementById('cropperModal');
        const imageCropper = document.getElementById('image-cropper');
        const cropBtn = document.getElementById('crop-btn');

        // Abrir el modal al seleccionar una imagen
        function openPhotoModal() {
            fileInput.click();
        }

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageCropper.src = e.target.result;
                    cropperModal.style.display = 'block'; // Mostrar modal
                    cropper = new Cropper(imageCropper, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Recortar la imagen y guardarla
        cropBtn.addEventListener('click', function() {
            const croppedCanvas = cropper.getCroppedCanvas({
                width: 150,
                height: 150,
            });

            const croppedImage = croppedCanvas.toDataURL(); // Convertir a Base64
            profilePic.src = croppedImage; // Mostrar la imagen recortada en el perfil
            localStorage.setItem('profileImage', croppedImage); // Guardar en localStorage

            cropperModal.style.display = 'none'; // Ocultar modal
            cropper.destroy(); // Destruir cropper instance
        });

        // Cargar la imagen del localStorage al cargar la página
        window.addEventListener('DOMContentLoaded', (event) => {
            const storedImage = localStorage.getItem('profileImage');
            if (storedImage) {
                profilePic.src = storedImage;
            }
        });
    </script>
</body>
</html>
