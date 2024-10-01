<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: trabajo_grado.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Consulta para obtener los valores de item1 a item12, fase, observaciones y enlaces
$sql = "SELECT item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, fase, observaciones, titulo_proyecto, tema_proyecto, companero_proyecto, plan, Anexos, Libro, Video, Pagina, comentario, lider
        FROM registros 
        WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$habilitar_fase2 = false;
$habilitar_fase3 = false;
$observacion = ""; // Inicializa la variable observacion

// Datos para los formularios
$tituloProyecto = $row ? htmlspecialchars($row['titulo_proyecto']) : '';
$temaProyecto = $row ? htmlspecialchars($row['tema_proyecto']) : '';
$linkPlan = $row ? htmlspecialchars($row['plan']) : '';
$linkAnexos = $row ? htmlspecialchars($row['Anexos']) : '';
$linkLibro = $row ? htmlspecialchars($row['Libro']) : '';
$linkVideo = $row ? htmlspecialchars($row['Video']) : '';
$linkPagina = $row ? htmlspecialchars($row['Pagina']) : '';
$lider = $row ? htmlspecialchars($row['lider']) : '';
$compañeroProyecto = $row ? htmlspecialchars($row['companero_proyecto']) : '';


if ($row) {
    // Verificar si todas las columnas item1 a item12 tienen valor "1"
    $todos_items_uno = true;
    for ($i = 1; $i <= 12; $i++) {
        if ($row["item$i"] != '1') {
            $todos_items_uno = false;
            break;
        }
    }
    // Verificar si fase es "fase1" para habilitar FASE2
    $fase_fase2 = ($row['fase'] == '1');
    // Verificar si fase es "fase2" para habilitar FASE3
    $fase_fase3 = ($row['fase'] == '2');

    // Habilitar el botón FASE2 si todas las condiciones se cumplen
    if ($todos_items_uno && $fase_fase2) {
        $habilitar_fase2 = true;
    }

    // Habilitar el botón FASE3 si todas las condiciones se cumplen
    if ($todos_items_uno && $fase_fase3) {
        $habilitar_fase2 = true;
        $habilitar_fase3 = true;
    }

    // Obtener la observación
    $observacion = $row['observaciones'];
    $comentario = $row['comentario'];
}

$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../ejemplo_base/logo_uis.png" type="image/png"> <!-- Ajusta la ruta y tipo según tu imagen -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularios de Proyecto</title>
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
        .formulario {
            display: none; /* Ocultar todos los formularios por defecto */
        }
        .btn-fases{ 
            margin: auto;  
            margin-left: 10px; 
            margin-bottom:20px;   
            height: 40px;   
            width: 130px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #1ba71b;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-fases:hover {
            transform: scale(1.1);
            background-color: #279c2d;
            color: #ffffff;
        }
        .menu{
            text-align: center;
        }

        .container-formulario {
            margin:auto;
            width: 70%;
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .container-formulario:hover{
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.301);
        }

        .formulario {
            margin-bottom: 20px;
        }

        .formulario h2 {
            text-align: center;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 8px;   
            background-color: #333;
            color: #fff;
        }

        .formulario form {
            display: flex;
            flex-direction: column;
        }

        .formulario label {
            font-family: 'Roboto', sans-serif;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .formulario input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .formulario button {   
            margin: auto;      
            height: 30px;   
            width: 120px;
            border: none;
            border-radius: 5px;
            background-color: #1ba71b;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .formulario button:hover {
            transform: scale(1.1);
            background-color: #279c2d;
            color: #fff;
        }
        #fixedDiv {
            position: fixed; /* Mantiene el div en una posición fija */
            bottom: 20px; /* Distancia desde el borde inferior */
            right: 20px; /* Distancia desde el borde derecho */
            width: 90px; /* Ancho del div */
            height: 90px; /* Alto del div */
            background-color: #007BFF; /* Color de fondo (puedes cambiarlo) */
            border-radius: 8px; /* Bordes redondeados (opcional) */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); /* Sombra para mejor visibilidad (opcional) */
            z-index: 1000; /* Asegura que esté sobre otros elementos */
            display: flex;
            align-items: center; /* Centra la imagen verticalmente */
            justify-content: center; /* Centra la imagen horizontalmente */
        }
        .observaciones{
            margin-top: 7px;
        }

        #logoImage {
            max-width: 100%; /* Ajusta el ancho máximo al tamaño del div */
            max-height: 100%; /* Ajusta la altura máxima al tamaño del div */
            border-radius: 8px; /* Opcional: igualar el borde redondeado del div */
        }

       
        /* Estilo para la ventana de notificación */
        .notificacion {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50; /* Color verde para éxito */
            color: white;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none; /* Oculta la notificación por defecto */
        }

        .notificacion.error {
            background-color: #f44336; /* Color rojo para error */
        }
        

    </style>
</head>
<body>
<img src="logoEscuela.png" alt="Logo Escuela" style="position: absolute; top: 40px; right: 60px; width: 100px; z-index: 1000;">

    <div class="sidebar">
        <a href="profile.php">Perfil de Usuario</a>
        <a href="trabajo_grado.php">Trabajo de Grado</a>
        <a href="Informacion_trabajo.html">Descripción</a>
        <a href="https://sites.google.com/e3t.uis.edu.co/carteradetemas?authuser=0" target="_blank">Cartera de proyectos</a>
        <a href="logout.php" style="background-color: #1ba71b;" >Cerrar sesión</a>
    </div>
    <div class="content">
        <nav>
            <h1>Trabajo de Grado</h1>
        </nav>
        <div class="menu">
            <button class="btn-fases" onclick="mostrarFormulario(1)">FASE 1</button>
            <button class="btn-fases" id="fase2-btn" onclick="mostrarFormulario(2)" <?php echo $habilitar_fase2 ? '' : 'disabled'; ?>>FASE 2</button>
            <button class="btn-fases" id="fase3-btn" onclick="mostrarFormulario(3)" <?php echo $habilitar_fase3 ? '' : 'disabled'; ?>>FASE 3</button>
        </div>
        <!-- Contenido de los formularios -->
         
        <div class="container-formulario">
            <!-- Formulario 1 -->
            <div class="formulario" id="formulario1">
                <h2>Título y Tema del Proyecto</h2>
                <form id="formProyecto" name="formProyecto" action="guardar_datos.php" method="POST">
                    <label for="tituloProyecto">Título del Proyecto:</label>
                    <input type="text" id="tituloProyecto" name="tituloProyecto" required placeholder="Ingrese el titulo del proyecto" value="<?php echo $tituloProyecto; ?>">
                    <br>
                    
                    <label for="temaProyecto">Subir Archivo PDF del tema del proyecto:</label>
                    <input id="myFile1" type="file">
                    <!-- Botón para generar la URL -->
                    <input class="btn-fases" type="button" value="Generar URL" onclick="handleFileUpload(event, 'myFile1', 'output1', 'urlHiddenInput1')">
                    <!-- Campo para mostrar el resultado de la URL generada -->
                    <div id="output1"></div>
                    <input type="hidden" id="urlHiddenInput1" name="temaProyecto">
                    <br>
                    ULR actual:</label>
                    <?php echo $temaProyecto; ?>
                    <br>
                    <br>
                    <label for="compañeroProyecto">Otros autores:</label>
                    <input type="text" id="compañeroProyecto" name="compañeroProyecto" placeholder="Otros autores" value="<?php echo $compañeroProyecto; ?>">
                    <br>
                    <label for="lider">Docente director del Proyecto :</label>
                    <input type="text" id="lider" name="lider" required placeholder="ingrese el nombre del Docente" value="<?php echo $lider; ?>">
                    <br>
                    <button type="submit">Guardar</button>
                </form>
                <br>
                <label for="comentario">OBSERVACIONES FASE 1:</label>
                <div class="container-formulario">
                
                    <div class="comentario">
                        <div>
                            <?php echo htmlspecialchars($comentario); ?>
                        </div>
                    </div>
                    <div id="fixedDiv">
                        <img src="logo_uis.png" alt="Logo UIS" id="logoImage">
                    </div>
                </div>
            </div>

            <!-- Formulario 2 -->
            <div class="formulario" id="formulario2">
                <h2>Plan, Pagina WEB</h2>
                <form id="formAvances" name="formAvances" action="guardar_datos.php" method="POST">
                    <label for="linkPlan">Adjunte documento PDF del Plan:</label>
                    <br>
                    <input id="myFile2" type="file">
                    <input class="btn-fases" type="button" value="Subir" onclick="handleFileUpload(event, 'myFile2', 'output2','urlHiddenInput2')">
                    <div id="output2"></div>
                    <input type="hidden" id="urlHiddenInput2" name="linkPlan">
                    <br>
                    ULR actual:</label>
                    <?php echo $linkPlan; ?>
                    <br>
                    <br>
                    <label for="linkPagina">Link de Pagina web:</label>
                    <input type="url" id="linkPagina" name="linkPagina" value="<?php echo $linkPagina; ?>" required placeholder="Ingrese el link de la pagina web">
                    <br>
                    <button type="submit">Guardar</button>
                </form>
                <br>
                <label for="observaciones">OBSERVACIONES FASE 2</label>
                <div class="container-formulario">
                    <div class="observaciones">
                        <div>
                            <?php echo htmlspecialchars($observacion); ?>
                        </div>
                    </div>
                    <div id="fixedDiv">
                        <img src="logo_uis.png" alt="Logo UIS" id="logoImage">
                    </div>
                </div>
            </div>

            <!-- Formulario 3 -->
            <div class="formulario" id="formulario3">
                <h2>Libro, Anexos y Video</h2>
                <form id="formLibroVideo" name="formLibroVideo" action="guardar_datos.php" method="POST">
                    Subir Documento PDF del Libro o Informe Final
                    <label for="linkLibro">Link del Libro:</label>
                    <br>
                    <input id="myFile3" type="file"  />
                    <input input class="btn-fases" type="button" value="Subir" onclick="handleFileUpload(event, 'myFile3', 'output3','urlHiddenInput3')">
                    <div id="output3"></div>
                    <input type="hidden" id="urlHiddenInput3" name="linkLibro">
                    <br>
                    <label for="linkVideo">Link del Video:</label>
                    <input type="url" id="linkVideo" name="linkVideo" value="<?php echo $linkVideo; ?>" required placeholder="Ingrese el link del Video">
                    <br>
                    Subir Archivo
                    <input id="myFile4" type="file"  />
                    <input input class="btn-fases" type="button" value="Subir" onclick="handleFileUpload(event, 'myFile4', 'output4','urlHiddenInput4')">
                    <div id="output4"></div>
                    <input type="hidden" id="urlHiddenInput4" name="linkAnexos">
                    <button type="submit">Guardar</button>
                </form>
            </div>

        </div>
       
    </div>
    <div id="notificacion" class="notificacion"></div>
    <script>


        // para mostrar el formulario 1 por defecto
        document.addEventListener("DOMContentLoaded", function() {
            mostrarFormulario(1);
        });


        let currentFormIndex = 0;
        const formularios = document.querySelectorAll('.formulario');

        function mostrarFormulario(numero) {
            if (numero <= formularios.length) {
                formularios.forEach(form => {
                    form.style.display = 'none';
                });
                formularios[numero - 1].style.display = 'block';
                currentFormIndex = numero - 1;
            }
        }

        function mostrarSiguienteFormulario() {
            const nextFormIndex = currentFormIndex + 1;
            if (nextFormIndex < formularios.length) {
                formularios[currentFormIndex].style.display = 'none';
                formularios[nextFormIndex].style.display = 'block';
                currentFormIndex = nextFormIndex;
            }
        }

        // Capturar el envío de cada formulario y enviar los datos a PHP para guardar en la base de datos
        document.getElementById('formProyecto').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar que se recargue la página al enviar el formulario
            guardarFormulario('formProyecto');
            mostrarSiguienteFormulario();
        });

        document.getElementById('formAvances').addEventListener('submit', function(event) {
            event.preventDefault();
            guardarFormulario('formAvances');
            mostrarSiguienteFormulario();
        });

        document.getElementById('formLibroVideo').addEventListener('submit', function(event) {
            event.preventDefault();
            guardarFormulario('formLibroVideo');
            mostrarSiguienteFormulario();
        });

        
        function mostrarNotificacion(mensaje, tipo) {
            const notificacion = document.getElementById('notificacion');
            notificacion.textContent = mensaje;

            if (tipo === 'error') {
                notificacion.classList.add('error');
            } else {
                notificacion.classList.remove('error');
            }

            notificacion.style.display = 'block';

            // Ocultar la notificación después de 3 segundos
            setTimeout(() => {
                notificacion.style.display = 'none';
            }, 3000);
        }

        function guardarFormulario(formularioId) {
            // Obtener datos del formulario
            let formData = new FormData(document.getElementById(formularioId));

            // Enviar datos al servidor PHP usando fetch
            fetch('guardar_datos.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud: ' + response.statusText);
                }
                return response.json(); // Procesar la respuesta como JSON
            })
            .then(data => {
                console.log('Datos guardados correctamente:', data);
                // Mostrar mensaje de éxito
                mostrarNotificacion('Datos guardados correctamente.', 'success');
            })
            .catch(error => {
                console.error('Error al enviar datos al servidor:', error);
                // Mostrar mensaje de error
                mostrarNotificacion('Error al enviar los datos. Por favor, inténtalo de nuevo.', 'error');
            });
        }

        // PARTE CODIGO PARA SUBIR ARCHIVOS AL DRIVE
        const folderId = "1kJ32AWfeGbYqlbqB2DrpmkPVnAIt3YVD";  // ID de la carpeta de destino

        function handleFileUpload(event, fileId, outputId, hiddenInputId) {
        const progressIndicator = document.createElement("div");
        progressIndicator.innerHTML = "Subiendo archivo...";
        document.getElementById(outputId).appendChild(progressIndicator);
        event.preventDefault();
        const fileInput = document.getElementById(fileId);
        const file = fileInput.files[0];

        if (!file) {
            alert("Por favor selecciona un archivo");
            return;
        }

        getFileBase64(file)
            .then(base64Data => uploadFileToDrive(file, base64Data))
            .then(response => {
                // Mostrar el URL en el div de output
                document.getElementById(outputId).innerHTML = "Archivo subido: <a href='" + response + "'>" + response + "</a>";
                
                // Almacenar el URL en el campo oculto
                document.getElementById(hiddenInputId).value = response;
            })
            .catch(error => {
                document.getElementById(outputId).innerHTML = "Error al subir el archivo: " + error;
            });
        
    }


    function getFileBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const base64Data = e.target.result.split(',')[1];
                resolve(base64Data);
            };
            reader.onerror = function (error) {
                reject("Error al leer el archivo: " + error);
            };
            reader.readAsDataURL(file);
        });
    }

    async function uploadFileToDrive(file, base64Data) {
        const folderId = "1kJ32AWfeGbYqlbqB2DrpmkPVnAIt3YVD";  // ID de la carpeta de destino
        const formData = new URLSearchParams({
            "filename": file.name,
            "mimeType": file.type,
            "data": base64Data,
            "folderId": folderId,
            "orden": "subirarchivo"
        });

        const response = await fetch('https://script.google.com/macros/s/AKfycbyhgaIPH9MGfM-yvEBxJZ4wCos7Ug-TKZoa4LAG4oyhVWup5qDKoFso7ClwJos0Z38WVw/exec', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Error en la subida: ' + response.statusText);
        }

        return response.text();
    }
    
  
    </script>
</body>