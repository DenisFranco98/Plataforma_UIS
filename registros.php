<?php
// Conectar a la base de datos
$host = 'localhost'; // Cambia esto si es necesario
$user = 'root';
$password = "E3t2023uis";
$database = 'usuarios';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$codigo_estudiante = $_POST['codigo_estudiante'];
$carrera = $_POST['carrera'];
$Celular = $_POST['Celular'];
$correo_gmail = $_POST['correo_gmail'];
$contraseña = $_POST['contraseña'];
$semestre = $_POST['semestre'];
$matricula = $_POST['matricula'];

// Verificar si el usuario ya está registrado por ID o correo electrónico
$sql = "SELECT * FROM registros WHERE ID = '$codigo_estudiante' OR usuario = '$correo_gmail'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Usuario ya registrado
    header("Location: registro.html?error=1");
    exit();
} else {
    // Insertar nuevo usuario
    $sql_insert = "INSERT INTO registros (nombre, ID, programa, usuario, Celular, contraseña, semestre, matricula) VALUES ('$nombre', '$codigo_estudiante', '$carrera', '$correo_gmail', '$Celular', '$contraseña', '$semestre', '$matricula')";

    if ($conn->query($sql_insert) === TRUE) {
        header("Location: index.html");
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

$conn->close();
?>
