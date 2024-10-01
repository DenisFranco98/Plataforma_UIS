<?php
session_start();
include('db.php'); // Incluye tu archivo de conexión a la base de datos aquí

header('Content-Type: application/json'); // Establece el tipo de contenido

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Sesión expirada.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION['usuario'];

    try {
        // Formulario 1: Título y Tema del Proyecto
        if (isset($_POST['tituloProyecto']) && isset($_POST['temaProyecto']) && isset($_POST['compañeroProyecto'])) {
            $tituloProyecto = limpiarDatos($_POST['tituloProyecto']);
            $temaProyecto = limpiarDatos($_POST['temaProyecto']);
            $compañeroProyecto = limpiarDatos($_POST['compañeroProyecto']);
            $lider = limpiarDatos($_POST['lider']);

            // Imprimir datos para depuración
            error_log("Título Proyecto: $tituloProyecto, Tema Proyecto: $temaProyecto, Compañero Proyecto: $compañeroProyecto,lider: $lider, Usuario: $usuario");

            $query = "UPDATE registros SET titulo_proyecto = ?, tema_proyecto = ?, companero_proyecto = ? , lider= ? WHERE usuario = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $tituloProyecto, $temaProyecto, $compañeroProyecto, $lider, $usuario);

            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar los datos de la fase 1.');
            }

            $stmt->close();
        }

        // Formulario 2: Link del Plan y Pagina
        if (isset($_POST['linkPlan']) && isset($_POST['linkPagina'])) {
            $linkPlan = limpiarDatos($_POST['linkPlan']);
            $linkPagina = limpiarDatos($_POST['linkPagina']);

            $query = "UPDATE registros SET plan = ?, Pagina = ? WHERE usuario = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $linkPlan, $linkPagina, $usuario);

            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar los datos de la fase 2.');
            }

            $stmt->close();
        }

        // Formulario 3: Libro y Video
        if (isset($_POST['linkLibro']) && isset($_POST['linkVideo']) && isset($_POST['linkAnexos'])) {
            $linkLibro = limpiarDatos($_POST['linkLibro']);
            $linkVideo = limpiarDatos($_POST['linkVideo']);
            $linkAnexos = limpiarDatos($_POST['linkAnexos']);
        
            $query = "UPDATE registros SET Libro = ?, Video = ?, Anexos = ? WHERE usuario = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $linkLibro, $linkVideo, $linkAnexos, $usuario);
        
            if (!$stmt->execute()) {
                throw new Exception('Error al actualizar los datos de la fase 3.');
            }
        
            $stmt->close();
        }        

        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    $conn->close();
}

function limpiarDatos($datos) {
    return htmlspecialchars(trim($datos));
}
?>
