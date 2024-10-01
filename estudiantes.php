<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes</title>
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
        .formulario {
            display: flex;
            justify-content: center; /* Centra el formulario horizontalmente */
            margin: 0 auto; /* Añade un margen automático para centrar */
        }
        form {
            width: 100%; /* Hace que el formulario ocupe todo el ancho del contenedor */
            max-width: 1200px; /* Opcional: establece un ancho máximo para el formulario */
        }
        .form-section-container {
            display: flex;
            justify-content: center; /* Centra las secciones horizontalmente */
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .form-section {
            flex: 1;
            min-width: 200px;
            text-align: left; /* Alinea el texto dentro de cada sección a la izquierda */
            padding: 10px; /* Añade padding interno para separar el contenido de los bordes */
            background-color: #fff; /* Fondo blanco para cada sección */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }
        .form-section h3 {
            margin-top: 0; /* Elimina el margen superior del título */
        }
        .form-section label {
            display: block;
            margin-bottom: 10px;
        }
        .chart-container {
            margin-top: 20px;
            margin: auto;
            width: 50%;
            padding-bottom: 7px;
            box-shadow: 0 10px 5px rgba(0, 0, 0, 0.5);
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 7px;
            text-align: center;
        }
        h3{
            text-align:center;
        }
        canvas {
            max-width: 100%;
        }
        button {   
            margin-left: 20px;      
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

        button:hover {
            transform: scale(1.1);
            background-color: #279c2d;
            color: #fff;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <h2>Estadísticas por Estudiante</h2>
        </nav>
        <div class="formulario">
            <form method="POST">
            <div class="form-section-container">
                <div class="form-section">
                    <h3>Semestre</h3>
                    <?php
                    $conn = new mysqli("localhost", "root", "E3t2023uis", "usuarios");
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT DISTINCT semestre FROM registros WHERE semestre IS NOT NULL AND semestre != '' ORDER BY semestre";
                    $result = $conn->query($sql);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $semestre = htmlspecialchars($row['semestre']);
                            $checked = isset($_POST['semestre']) && in_array($semestre, $_POST['semestre']) ? 'checked' : '';
                            echo "<label><input type='checkbox' name='semestre[]' value='$semestre' $checked> Semestre $semestre</label>";
                        }
                    }

                    $conn->close();
                    ?>
                </div>

                <div class="form-section">
                    <h3>Matrícula</h3>
                    <?php
                    $conn = new mysqli("localhost", "root", "E3t2023uis", "usuarios");
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT DISTINCT matricula FROM registros WHERE matricula IS NOT NULL AND matricula != '' ORDER BY matricula";
                    $result = $conn->query($sql);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $matricula = htmlspecialchars($row['matricula']);
                            $checked = isset($_POST['matricula']) && in_array($matricula, $_POST['matricula']) ? 'checked' : '';
                            echo "<label><input type='checkbox' name='matricula[]' value='$matricula' $checked> Matrícula $matricula</label>";
                        }
                    }

                    $conn->close();
                    ?>
                </div>

                <div class="form-section">
                    <h3>Programa</h3>
                    <?php
                    $conn = new mysqli("localhost", "root", "E3t2023uis", "usuarios");
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT DISTINCT programa FROM registros WHERE programa IS NOT NULL AND programa != '' ORDER BY programa";
                    $result = $conn->query($sql);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $programa = htmlspecialchars($row['programa']);
                            $checked = isset($_POST['programa']) && in_array($programa, $_POST['programa']) ? 'checked' : '';
                            echo "<label><input type='checkbox' name='programa[]' value='$programa' $checked> Programa $programa</label>";
                        }
                    }

                    $conn->close();
                    ?>
                </div>

                <div class="form-section">
                    <h3>Fase</h3>
                    <?php
                    $conn = new mysqli("localhost", "root", "E3t2023uis", "usuarios");
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT DISTINCT fase FROM registros WHERE fase IS NOT NULL AND fase != '' ORDER BY fase";
                    $result = $conn->query($sql);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $fase = htmlspecialchars($row['fase']);
                            $checked = isset($_POST['fases']) && in_array($fase, $_POST['fases']) ? 'checked' : '';
                            echo "<label><input type='checkbox' name='fases[]' value='$fase' $checked> Fase $fase</label>";
                        }
                    }

                    $conn->close();
                    ?>
                </div>

                </div>
                <button type="submit" name="generate_chart">Generar Gráfico</button>
            </form>
        </div>

        <?php
        if (isset($_POST['generate_chart'])) {
            $conn = new mysqli("localhost", "root", "E3t2023uis", "usuarios");

            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $semestre = isset($_POST['semestre']) ? array_map([$conn, 'real_escape_string'], $_POST['semestre']) : [];
            $matricula = isset($_POST['matricula']) ? array_map([$conn, 'real_escape_string'], $_POST['matricula']) : [];
            $programa = isset($_POST['programa']) ? array_map([$conn, 'real_escape_string'], $_POST['programa']) : [];
            $fases = isset($_POST['fases']) ? array_map([$conn, 'real_escape_string'], $_POST['fases']) : [];

            $query = "SELECT CONCAT_WS(' - ', programa, semestre, matricula, fase) as filtro, COUNT(*) as total
                      FROM registros WHERE 1";

            if (!empty($semestre)) {
                $query .= " AND semestre IN ('" . implode("','", $semestre) . "')";
            }
            if (!empty($matricula)) {
                $query .= " AND matricula IN ('" . implode("','", $matricula) . "')";
            }
            if (!empty($programa)) {
                $query .= " AND programa IN ('" . implode("','", $programa) . "')";
            }
            if (!empty($fases)) {
                $query .= " AND fase IN ('" . implode("','", $fases) . "')";
            }

            $query .= " GROUP BY CONCAT_WS(' - ', programa, semestre, matricula, fase)";

            $result = $conn->query($query);

            if (!$result) {
                die("Error en la consulta: " . $conn->error);
            }

            $data = [];
            $labels = [];

            while ($row = $result->fetch_assoc()) {
                $filtro = htmlspecialchars($row['filtro']);
                $total = $row['total'];
                $labels[] = "$filtro: $total estudiantes"; // Etiqueta personalizada
                $data[] = $total;
            }

            $conn->close();
        }
        ?>

        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>

        <script>
            <?php if (isset($data) && !empty($data)) { ?>
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie', // Gráfico de pastel
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: 'Número de Estudiantes',
                            data: <?php echo json_encode($data); ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)', // Colores vivos
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)',
                                'rgba(199, 199, 199, 0.6)',
                                'rgba(83, 102, 255, 0.6)',
                                'rgba(255, 99, 71, 0.6)',
                                'rgba(255, 215, 0, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(199, 199, 199, 1)',
                                'rgba(83, 102, 255, 1)',
                                'rgba(255, 99, 71, 1)',
                                'rgba(255, 215, 0, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw + ' estudiantes';
                                    }
                                }
                            }
                        }
                    }
                });
            <?php } ?>
        </script>
    </div>
</body>
</html>