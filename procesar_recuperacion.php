<?php
session_start();
include 'db.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos aquí

// Verificar conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];

    // Generar una contraseña aleatoria
    $nueva_contraseña = generarContraseñaAleatoria(); // Define esta función más adelante

    // Encriptar la nueva contraseña
    $contraseña_encriptada = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

    // Actualizar la base de datos con la nueva contraseña encriptada
    $sql = "UPDATE registros SET contraseña = '$contraseña_encriptada' WHERE usuario = '$correo'";

    if ($conn->query($sql) === TRUE) {
        // Envío de correo electrónico con la nueva contraseña
        enviarCorreo($correo, $nueva_contraseña);

        echo "Se ha enviado una nueva contraseña al correo proporcionado.";
    } else {
        echo "Error al actualizar la contraseña: " . $conn->error;
    }
}

// Función para generar una contraseña aleatoria
function generarContraseñaAleatoria() {
    $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitud_contraseña = 10;
    return substr(str_shuffle($caracteres_permitidos), 0, $longitud_contraseña);
}

// Función para enviar correo electrónico
function enviarCorreo($correo, $nueva_contraseña) {
    $asunto = "Recuperación de Contraseña";
    $mensaje = "Su nueva contraseña es: $nueva_contraseña";
    $cabeceras = "From: tu_correo@gmail.com";

    if (mail($correo, $asunto, $mensaje, $cabeceras)) {
        echo "Se ha enviado una nueva contraseña al correo proporcionado.";
    } else {
        echo "Error al enviar el correo electrónico.";
    }
}

// Cerrar conexión a la base de datos
$conn->close();
?>
