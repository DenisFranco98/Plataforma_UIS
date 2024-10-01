<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['usuario'];
    $password = $_POST['contraseña'];

    // Consulta para verificar las credenciales
    $query = "SELECT * FROM registros WHERE usuario = ? AND contraseña = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifica si es el administrador
        if ($email == 'e3t.uis2023@gmail.com' && $password == 'E3t2023uis') {
            $_SESSION['admin'] = true;
            header("Location: ver_registros.php");
            exit;
        } else {
            // Redirigir al perfil del usuario normal
            $_SESSION['usuario'] = $email; // Asegúrate de que 'usuario' sea la columna correcta
            header("Location: profile.php");
            exit;
        }
    } else {
        header("Location: index.html?error=1"); // Redirigir con mensaje de error
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>







