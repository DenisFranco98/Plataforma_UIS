<?php
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

$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Preparar la consulta
$stmt = $conn->prepare("SELECT * FROM registros WHERE 
        (ID LIKE ? OR 
        nombre LIKE ? OR 
        companero_proyecto LIKE '%$search%' OR 
        programa LIKE ? OR 
        titulo_proyecto LIKE ? OR 
        lider LIKE ? OR 
        tema_proyecto LIKE ? OR 
        plan LIKE ? OR 
        Anexos LIKE ? OR 
        Libro LIKE ? OR 
        Video LIKE ? OR 
        usuario LIKE ?)
        AND usuario != 'e3t.uis2023@gmail.com'");

$search_term = "%$search%";
$stmt->bind_param("sssssssssss", $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error en la consulta: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../ejemplo_base/logo_uis.png" type="image/png"> <!-- Ajusta la ruta y tipo según tu imagen -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones</title>
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;
            background-color: #f1f1f5b6;
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

        .sidebar {
            width: 200px;
            background-color: #333;
            padding: 15px;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1;
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

        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        table {
            width: 100%;
            cursor: default;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: bold;
            border-collapse: collapse;
            box-shadow: 3px 4px 8px rgba(0, 0, 0);
        }

        .table-container {
            padding: 44px;
            overflow: auto;
        }

        th, td {
            border: 1px solid black;
            background-color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th {
            background-color: #1ba71bde;
            color: white;
            text-align: center;
        }

        tr:hover td {
            background-color: rgba(99, 98, 98, 0.2);
        }

        .search-container {
            margin-bottom: 10px;
            max-width: 70%;
        }

        .barra-search {
            width: 40% !important;
            padding-left: 10px;
            margin-right: 10px;
            margin-left: 20px;
            height: 35px;
            border: none;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .barra-search:focus {
            outline: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0);
        }

        .boton-con-imagen {
            width: 30px;
            height: 30px;
            background: transparent;
            background-image: url('search.png');
            background-size: contain;
            background-repeat: no-repeat;
            border: none;
            cursor: pointer;
            color: transparent;
            font-size: 0;
        }

        .btn {
            margin: auto;
            height: 20px;
            width: 110px;
            padding: 3px 5px;
            border: none;
            border-radius: 5px;
            background-color: #1ba71b;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            text-align: center;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.02);
            background-color: #1ba71b;
            color: #fff;
            text-decoration: none;
        }

        .formulario-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin: Auto;
            Margin top: 30px;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: auto; /* Permite el desplazamiento */
            z-index: 2;
        }
        
        .formulario {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            max-height: 80%; /* Limita la altura máxima */
            overflow-y: auto; /* Agrega una barra de desplazamiento vertical si es necesario */
            margin: Auto;
            
        }

        .formulario label {
            display: block;
            margin-bottom: 10px;
        }

        .formulario select, .formulario textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .formulario-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        textarea {
            resize: none;
            max-width: 100%;
            max-height: 150px;
            overflow-y: auto;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .formulario {
                width: 100%;
                max-width: none;
            }
        }

    </style>
</head>
<body>
 <img src="logoEscuela.png" alt="Logo Escuela" style="position: absolute; top: 40px; right: 60px; width: 100px; z-index: 1000;">

    <div class="sidebar">
        <a href="ver_registros.php">Base de datos</a>
        <a href="calificaciones.php">Calificaciones</a>
        <a href="estudiantes.php">Estudiantes</a>
        <a href="logout.php" style="background-color: #1ba71b;" >Cerrar sesión</a>
    </div>
    <div class="main-content">
        <nav>
            <h1>Calificaciones</h1>
        </nav>
        <div class="search-container">
            <form class="formulario-search" method="POST">
                <input class="barra-search" type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar..." />
                <input type="submit" value="Buscar" class="boton-con-imagen">
            </form>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Estudiante 1</th>
                        <th>Nombre Estudiante 2</th>
                        <th>Programa</th>
                        <th>Título del Proyecto</th>
                        <th>Docente Líder</th>
                        <th>Fase 1</th>
                        <th>Fase 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['companero_proyecto']); ?></td>
                        <td><?php echo htmlspecialchars($row['programa']); ?></td>
                        <td><?php echo htmlspecialchars($row['titulo_proyecto']); ?></td>
                        <td><?php echo htmlspecialchars($row['lider']); ?></td>
                        <td>
                            <button class="btn" onclick="mostrarFormulario(<?php echo $row['ID']; ?>, '1')">FASE 1</button>
                        </td>
                        <td>
                            <button class="btn" onclick="mostrarFormulario(<?php echo $row['ID']; ?>, '2')">FASE 2</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulario Overlay -->
    <div class="formulario-overlay" id="formulario-overlay" style="display: none;">
        <div class="formulario" id="formulario-calificacion">
            <form method="post" action="form_calif.php">
                <input type="hidden" name="registro_id" id="registro_id">
                <input type="hidden" name="fase" id="fase">
                <div id="formulario-fase1" style="display: none;">
                    <h3>Formulario Fase 1</h3>
                    <label for="item1">Objetivo General.</label>
                    <select name="item1" id="item1">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>
                    </br>
                    <label for="item2">Justificación.</label>
                    <select name="item2" id="item2">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item3">Identificación de restricciones.</label>
                    <select name="item3" id="item3">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item4">Estándares de ingeniería o normas.</label>
                    <select name="item4" id="item4">
                        <option value="1">Contiene</option>
                        <option value="2">No contiene</option>
                    </select>

                    <label for="item5">¿El trabajo posee múltiples soluciones?</label>
                    <select name="item5" id="item5">
                        <option value="1">Contiene</option>
                        <option value="2">No contiene</option>
                    </select>

                    <label for="item6">El trabajo tiene impactos que solucionen una necesidad o probrema?.</label>
                    <select name="item6" id="item6">
                    <option value="1">Contiene</option>
                    <option value="2">No contiene</option>
                    </select>

                    <label for="item7">Aplica asignaturas y habilidades adquiridas en el programa que aportan al proyecto.</label>
                    <select name="item7" id="item7">
                    <option value="1">Contiene</option>
                    <option value="2">No contiene</option>
                    </select>

                    <label for="item8">La modalidad del proyecto esta bien asignada al tema a tratar?.</label>
                    <select name="item8" id="item8">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item9">Contiene una entidad Interesada en dicho proyecto?.</label>
                    <select name="item9" id="item9">
                        <option value="1">Contiene</option>
                        <option value="2">No contiene</option>
                    </select>

                    <label for="item10">Contiene palabras Clave asociadas al proyecto.</label>
                    <select name="item10" id="item10">
                        <option value="1">Contiene</option>
                        <option value="2">No contiene</option>
                    </select>

                    <label for="item11">Sabe distinguir correctamente el area del Proyecto a trabajar.</label>
                    <select name="item11" id="item11">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item12">Contiene Director del Proyecto</label>
                    <select name="item12" id="item12">
                        <option value="1">Contiene</option>
                        <option value="2">No contiene</option>
                    </select>

                    <div class="container-textarea">
                        <label for="comentario">Escriba Observacion calificativa sobre el proyecto.</label>
                        <textarea name="comentario" id="comentario" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div id="formulario-fase2" style="display: none;">
                    <h3>Formulario Fase 2</h3>
                    <!-- Campos para Fase 2 -->
                    <!-- Agrega aquí los campos específicos de Fase 2 -->
                    <label for="item1">La entrada de la introducción contiene información que sorprende al lector por la calidad de la información? ¿eleva la credibilidad en el escritor por su originalidad?  logra atrapar la atención del lector al punto de sentirse motivado para seguir leyendo?</label>
                    <select name="item1" id="item1">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>
                    
                    <label for="item2">El cuerpo de la introducción aborda la problemática global de la que nace el problema concreto a resolver en el proyecto? ¿brinda un contexto para preparar a un lector, que, aunque es ingeniero, no es experto en el tema puntual, para comprender la propuesta y valorarla? ¿para un inversor interesado en el tema esto podría ser suficiente para querer conocer más, o suspendería la lectura?</label>
                    <select name="item2" id="item2">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item3">La introducción es tan completa como para lograr el objetivo de que el lector pueda comprender todo el trabajo con solo leer la introducción?</label>
                    <select name="item3" id="item3">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item4">El cierre de la introducción logra el objetivo de sorprender al lector al conocer que el trabajo tiene impactos que van más allá de lo obvio? ¿si el lector fuese un inversor, el cierre lograría el efecto de hacerlo pensar en invertir en el proyecto?</label>
                    <select name="item4" id="item4">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item5">El capítulo de Definición del problema existe y comienza por presentar el problema específico que el proyecto debe resolver? ¿es específica para los objetivos?  ¿es concreta, sin generar ambigüedad?  </label>
                    <select name="item5" id="item5">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item6">¿El capítulo de Definición del problema contiene listas de las variables, restricciones y criterios, los explica?</label>
                    <select name="item6" id="item6">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item7">El capítulo de Definición del problema en la lista de variables, restricciones y criterios hay un esfuerzo por considerar: ¿estándares o normas o políticas? Y los aspectos no técnicos como lo: ¿ambiental, social, de salud, seguridad, economía)?</label>
                    <select name="item7" id="item7">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item8">¿Siguiendo los objetivos planteados, el problema será resuelto?</label>
                    <select name="item8" id="item8">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item9">Los estudios previos incluyen resultados de la exploración empática que realmente aportan a una solución del problema en beneficio particular de los interesados? ¿queda claro quiénes son los actores o interesados en la solución? ¿hay coherencia entre las manifestaciones de esos actores y los compromisos que este proyecto asume?</label>
                    <select name="item9" id="item9">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item10">Los estudios previos presentan un análisis de las causas del problema mayor e identifican la causa o causas que ataca este proyecto?</label>
                    <select name="item10" id="item10">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item11">Los estudios previos analizan antecedentes que sirven como punto de partida para el proyecto? ¿se analizan experiencias previas locales o internacionales?</label>
                    <select name="item11" id="item11">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <label for="item12">Los estudios previos incluyen la ideación? ¿en la ideación divergente se han considerado las ideas más audaces? ¿la ideación convergente demuestra que el proyecto se enfoca en la mejor solución, en idea que más conviene bien sea por el interés de los actores o de otras causas como económicas, de protección al medio ambiente, Etc?</label>
                    <select name="item12" id="item12">
                        <option value="1">Excelente</option>
                        <option value="2">Bueno</option>
                        <option value="3">Malo</option>
                    </select>

                    <div class="container-textarea">
                        <label for="observaciones">Escriba Observacion calificativa sobre el proyectos</label>
                        <textarea name="observaciones" id="observaciones" cols="30" rows="10"></textarea>
                    </div>
                </div>

                <div class="formulario-buttons">
                    <button type="submit" class="btn">Guardar</button>
                    <button type="button" class="btn" onclick="ocultarFormulario()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        function mostrarFormulario(id, fase) {
            // Establece el valor de la fase en el campo oculto
            document.getElementById('fase').value = fase;
            document.getElementById('registro_id').value = id;
            
            // Muestra el formulario correspondiente
            if (fase === '1') {
                document.getElementById('formulario-fase1').style.display = 'block';
                document.getElementById('formulario-fase2').style.display = 'none';
            } else {
                document.getElementById('formulario-fase1').style.display = 'none';
                document.getElementById('formulario-fase2').style.display = 'block';
            }
            
            // Muestra el overlay
            document.getElementById('formulario-overlay').style.display = 'block';
        }

        function ocultarFormulario() {
            document.getElementById("formulario-overlay").style.display = 'none';
        }

        function enviarDatos() {
            var id = window.currentUserId;
            var fase = document.getElementById("fase").value;
            var item1 = document.getElementById("item1").value;
            var item2 = document.getElementById("item2").value;
            var item3 = document.getElementById("item3").value;
            var item4 = document.getElementById("item4").value;
            var item5 = document.getElementById("item5").value;
            var item6 = document.getElementById("item6").value;
            var item7 = document.getElementById("item7").value;
            var item8 = document.getElementById("item8").value;
            var item9 = document.getElementById("item9").value;
            var item10 = document.getElementById("item10").value;
            var item11 = document.getElementById("item11").value;
            var item12 = document.getElementById("item12").value;
            var observaciones = document.getElementById("observaciones").value;
            var comentario = document.getElementById("comentario").value;

            var datos = {
                id: id,
                fase: fase,
                item1: item1,
                item2: item2,
                item3: item3,
                item4: item4,
                item5: item5,
                item6: item6,
                item7: item7,
                item8: item8,
                item9: item9,
                item10: item10,
                item11: item11,
                item12: item12,
                comentario: comentario,
                observaciones: observaciones
            };

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "guardar_datos.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert("Datos guardados correctamente.");
                        ocultarFormulario();
                    } else {
                        console.error("Error al guardar los datos: " + xhr.status);
                    }
                }
            };

            var params = Object.keys(datos).map(function(key) {
                return key + '=' + encodeURIComponent(datos[key]);
            }).join('&');

            xhr.send(params);
        }
    </script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>